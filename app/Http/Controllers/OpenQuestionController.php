<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\OpenQuestion;
use App\Models\Subject;
use App\Models\Course;

class OpenQuestionController extends Controller
{
    private $openquestion;
    private $subject;
    private $course;

    public function __construct(OpenQuestion $openquestion, Subject $subject, Course $course) {
        $this->openquestion = $openquestion;
        $this->subject = $subject;
        $this->course = $course;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->openquestion->query();

        if (isset($request->subject_id)) {
            $query->where('subject_id',$request->subject_id);
        }
        
        $subjects = $this->subject->where('status','sim')->get();

        $openquestions = $query->orderBY('id','DESC')->paginate(10);

        return view('admin.openquestions.index',[
            'openquestions' => $openquestions,
            'subjects' => $subjects
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function list ($id) 
    {
        $subjects = $this->subject->where('course_id', '=', $id)->get();
        return view('admin.openquestions.search',['subjects' => $subjects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = $this->course->where('status','sim')->get();
        return view('admin.openquestions.create',['courses' => $courses]);
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
            'question' => 'required|string',
            'punctuation' => 'required|integer',
        ])->validate();

        if($this->openquestion->create($data)) {
            return redirect('admin/openquestions')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/openquestions/create')->with('error', 'Erro ao inserir o registro!');
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
        
        $question = $this->openquestion->find($id);
        if ($question) {
            return view('admin.openquestions.edit',['question' => $question, 'courses' => $courses, 'subjects' => $subjects]);
        }

        return redirect('admin/openquestions')->with('alert', 'Registro não encontrado!');
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
        $question = $this->openquestion->find($id);

        if (!$question) {
            return redirect('admin/openquestions')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'course_id' => 'required',
            'subject_id' => 'required',
            'question' => 'required|string',
            'punctuation' => 'required|integer',
        ])->validate();

        if($question->update($data)) {
            return redirect('admin/openquestions')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/openquestions')->with('error', 'Erro ao alterado o registro!');
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
        $data = $this->openquestion->find($id);
        if($data->delete()) {
            return redirect('admin/openquestions')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/openquestions')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
