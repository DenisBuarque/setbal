<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Mural;
use App\Models\Course;
use App\Models\Subject;

class MuralController extends Controller
{
    private $mural;
    private $course;
    private $subject;

    public function __construct(Mural $mural, Course $course, Subject $subject) 
    {
        $this->mural = $mural;
        $this->course = $course;
        $this->subject = $subject;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->mural->query();

        $search = $request->course;
        $search2 = $request->title;

        if (isset($search)) {
            $query->where('subject_id', $search);
        }

        if (isset($search2)) {
            $columns = ['title'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $search2 . '%');
            }
        }

        $subjects = $this->subject->where('status','sim')->get();

        $murals = $query->orderBY('id','DESC')->paginate(10);
        return view('admin.murals.index', [
            'murals' => $murals,
            'subjects' => $subjects,
            'search' => $search,
            'search2' => $search2,
        ]);
    }

    public function list ($id) 
    {
        $subjects = $this->subject->where('status','sim')->where('course_id', '=', $id)->get();
        return view('admin.murals.search',['subjects' => $subjects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = $this->course->where('status','sim')->get();
        $murals = $this->mural->all();
        return view('admin.murals.create',['murals' => $murals, 'courses' => $courses]);
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
            'course_id' => 'required',
            'subject_id' => 'required',
            'date' => 'required',
            'title' => 'required|string|max:100',
            'description' => 'required|string',
        ])->validate();

        if($this->mural->create($data)) {
            return redirect('admin/murals')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/murals/create')->with('error', 'Erro ao inserir o registro!');
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
        $subjects = $this->subject->where('course_id',$id)->get();
        
        $mural = $this->mural->find($id);
        if ($mural) {
            return view('admin.murals.edit',['mural' => $mural, 'courses' => $courses, 'subjects' => $subjects]);
        }

        return redirect('admin/murals')->with('alert', 'Registro não encontrado!');
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
        $mural = $this->mural->find($id);

        if (!$mural) {
            return redirect('admin/murals')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'course_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required|string|max:100',
            'description' => 'required|string',
        ])->validate();

        if($mural->update($data)) {
            return redirect('admin/murals')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/murals')->with('error', 'Erro ao alterado o registro!');
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
        $data = $this->mural->find($id);
        if($data->delete()) 
        {
            return redirect('admin/murals')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/murals')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
