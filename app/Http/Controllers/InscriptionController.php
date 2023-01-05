<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Inscription;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;

class InscriptionController extends Controller
{
    private $inscription;
    private $course;
    private $subject;
    private $user;

    public function __construct (Inscription $inscription, Course $course, Subject $subject, User $user) {
        $this->inscription = $inscription;
        $this->course = $course;
        $this->subject = $subject;
        $this->user = $user;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inscription_total = $this->inscription->count();
        $inscription_payment = $this->inscription->where('status','pago')->count();
        $inscription_pendent = $this->inscription->where('status','pendente')->count();
        $subjects = $this->subject->where('status','sim')->get();

        $query = $this->inscription->query();

        $search = $request->subject;
        $search2 = $request->status;

        if (!empty($search)) {
            $query->where('subject_id', $search);
        }

        if (!empty($search2)) {
            $query->where('status', $search2);
        }

        $inscriptions = $query->orderBy('id','DESC')->paginate(10);
        return view('admin.inscriptions.index',[
            'subjects' => $subjects,
            'inscriptions' => $inscriptions,
            'inscription_total' => $inscription_total,
            'inscription_payment' => $inscription_payment,
            'inscription_pendent' => $inscription_pendent,
            'subjects' => $subjects,
            'search' =>$search,
            'search2' =>$search2,
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
        $subjects = $this->subject->where('status','sim')->get();

        $students = DB::table('users')
                    ->where('users.nivel','student')
                    ->where('users.active','ativo')
                    ->join('registrations', 'users.id', '=', 'registrations.user_id')
                    ->where('registrations.payment','sim')
                    ->select(['users.*'])
                    ->get();

        return view('admin.inscriptions.create', [
            'courses' => $courses,
            'subjects' => $subjects,
            'students' => $students,
        ]);
    }

    public function list ($id) 
    {
        $subjects = $this->subject->where('course_id', '=', $id)->where('status','sim')->get();
        return view('admin.inscriptions.search',['subjects' => $subjects]);
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
            'user_id' => 'required',
            'date_inscription' => 'required',
            'closing_date' => 'required'
        ])->validate();

        if($this->inscription->create($data)) {
            return redirect('admin/inscriptions')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/inscriptions')->with('error', 'Erro ao inserir o registro!');
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
        $inscription = $this->inscription->find($id);

        $courses = $this->course->where('status','sim')->get();
        $subjects = $this->subject->where('status','sim')->where('course_id', $inscription->course_id)->get();
        $students = DB::table('users')
                    ->where('users.nivel','student')
                    ->where('users.active','ativo')
                    ->join('registrations', 'users.id', '=', 'registrations.user_id')
                    ->where('registrations.payment','sim')
                    ->select(['users.*'])
                    ->get();

        if ($inscription) {
            return view('admin.inscriptions.edit', [
                'courses' => $courses, 
                'subjects' => $subjects,
                'students' => $students,
                'inscription' => $inscription
            ]);

        } else {
            return redirect('admin/inscriptions')->with('alert', 'Registro não encontrado!'); 
        }
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
        $inscription = $this->inscription->find($id);

        if (!$inscription) {
            return redirect('admin/inscriptions')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'course_id' => 'required',
            'subject_id' => 'required',
            'user_id' => 'required',
            'date_inscription' => 'required',
            'closing_date' => 'required'
        ])->validate();

        if($inscription->update($data)) {
            return redirect('admin/inscriptions')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/inscriptions')->with('error', 'Erro ao alterado o registro!');
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
        $data = $this->inscription->find($id);
        if ($data->delete()) {
            return redirect('admin/inscriptions')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/inscriptions')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
