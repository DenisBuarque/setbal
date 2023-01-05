<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Registration;
use App\Models\Course;
use App\Models\Contract;

class RegistrationController extends Controller
{
    private $user;
    private $registration;
    private $course;
    private $contract;

    public function __construct(User $user, Registration $registration, Course $course, Contract $contract) {
        $this->user = $user;
        $this->registration = $registration;
        $this->course = $course;
        $this->contract = $contract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $total = $this->registration->count();
        $payment_on = $this->registration->where('payment','sim')->count();
        $payment_off = $this->registration->where('payment','nao')->count();
        $courses = $this->course->where('status','sim')->get();

        $query = $this->registration->query();

        $search = $request->course;
        $search2 = $request->payment;

        if (!empty($search)) {
            $query->where('course_id', $search);
        }

        if (!empty($search2)) {
            $query->where('payment', $search2);
        }

        $registrations = $query->orderBy('id','DESC')->paginate(10);

        return view('admin.registrations.index',[
            'total' => $total,
            'payment_on' => $payment_on,
            'payment_off' => $payment_off,
            'courses' => $courses,
            'registrations' => $registrations,
            'search' => $search,
            'search2' =>$search2
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
        $contracts = $this->contract->all();
        $students = $this->user->where('active','ativo')->where('nivel','student')->orderBY('name','ASC')->get();

        return view('admin.registrations.create', [
            'courses' => $courses, 
            'students' => $students, 
            'contracts' => $contracts
        ]);
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
            'user_id' => 'required',
            'contract_id' => 'required',
        ])->validate();

        if($this->registration->create($data)) {
            return redirect('admin/registrations')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/registrations')->with('error', 'Erro ao inserir o registro!');
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
        $contracts = $this->contract->all();
        $students = $this->user->where('active','ativo')->where('nivel','student')->get();

        $registration = $this->registration->find($id);
        if ($registration) {
            return view('admin.registrations.edit', [
                'registration' => $registration, 
                'courses' => $courses, 
                'students' => $students,
                'contracts' => $contracts
            ]);
        } else {
            return redirect('admin/registrations')->with('alert', 'Registro não encontrado!'); 
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
        $registration = $this->registration->find($id);

        if (!$registration) {
            return redirect('admin/registrations')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'course_id' => 'required',
            'user_id' => 'required',
            'contract_id' => 'required',
        ])->validate();

        if($registration->update($data)) {
            return redirect('admin/registrations')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/registrations')->with('error', 'Erro ao alterado o registro!');
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
        $data = $this->registration->find($id);
        if ($data->delete()) {
            return redirect('admin/registrations')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/registrations')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
