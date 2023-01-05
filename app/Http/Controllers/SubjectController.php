<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Subject;
use App\Models\Course;

class SubjectController extends Controller
{
    private $subject;
    private $course;

    public function __construct(Subject $subject, Course $course) {
        $this->subject = $subject;
        $this->course = $course;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->subject->query();
        
        $search = $request->search;
        $search2 = $request->course;

        if (isset($search2)) {
            $query->where('course_id', $search2);
        }
       
        if (isset($search)) {
            $columns = ['title','type','status'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $request->search . '%');
            }
        }

        $disciplines = $this->subject->all();
        $active = $this->subject->where('status','sim')->get();
        $inative = $this->subject->where('status','nao')->get();
        $quiz = $this->subject->where('quiz','liberado')->get();

        $courses = $this->course->all();

        $subjects = $query->orderBY('id','DESC')->paginate(10);

        return view('admin.subjects.index',[
            'subjects' => $subjects,
            'courses' => $courses,
            'disciplines' => $disciplines,
            'active' => $active,
            'inative' => $inative,
            'quiz' => $quiz,
            'search' => $search,
            'search2' => $search2 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = $this->course->where('status','sim')->get();
        return view('admin.subjects.create', ['courses' => $courses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'type' => 'required',
            'course_id' => 'required',
            'title' => 'required|string|unique:courses|max:100',
            'description' => 'required|string'
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($this->subject->create($data)) {
            return redirect('admin/subjects')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/subjects/create')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $courses = $this->course->all();

        $subject = $this->subject->find($id);
        if(!$subject) {
            return redirect('admin/subjects')->with('error', 'Registro não encontrado!');
        }

        return view('admin.subjects.edit', [
            'subject' => $subject, 
            'courses' => $courses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $subject = $this->subject->find($id);

        if (!$subject) {
            return redirect('admin/subjects')->with('error', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'type' => 'required',
            'course_id' => 'required',
            'title' => 'required|string|unique:courses|max:100',
            'description' => 'required|string'
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($subject->update($data)) {
            return redirect('admin/subjects')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/subjects')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->subject->find($id);
        if ($data->delete()) {
            return redirect('admin/subjects')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/subjects')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
