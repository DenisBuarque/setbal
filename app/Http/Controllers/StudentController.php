<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Course;
use App\Models\Polo;

class StudentController extends Controller
{
    private $user;
    private $course;
    private $polo;

    public function __construct(User $user, Course $course, Polo $polo) {
        $this->user = $user;
        $this->course = $course;
        $this->polo = $polo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $total_users = $this->user->where('nivel','student')->count();
        $student_setbal = $this->user->where('nivel','student')->where('local','setbal')->count();
        $student_ead = $this->user->where('nivel','student')->where('local','ead')->count();
        $student_active = $this->user->where('nivel','student')->where('active','inativo')->count();

        $query = $this->user->query(); // consulta a tabela de users
        
        $search = $request->search;
        $search2 = $request->local;

        if (isset($search2)) {
            $query->where('local',$search2)->where('nivel','student');
        }
        
        if (isset($search)) {
            $columns = ['name','city'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $request->search . '%');
            }
        }

        $courses = $this->course->all();

        $students = $query->where('nivel','student')->orderBY('id','DESC')->paginate(10);

        return view('admin.students.index',[
            'students' => $students,
            'courses' => $courses,
            'total_users' => $total_users,
            'student_setbal' => $student_setbal,
            'student_ead' => $student_ead,
            'student_active' => $student_active,
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
        $polos = $this->polo->all();
        return view('admin.students.create', ['polos' => $polos]);
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
            'polo_id' => 'required',
            'name' => 'required|string|max:100',
            'rg' => 'required|string|max:30|unique:students',
            'cpf' => 'required|string|max:15|unique:students',
            'phone' => 'required|string|max:16',
            'zip_code' => 'required|string|max:9',
            'address' => 'required|string|max:250',
            'district' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
            'local' => 'required|string',
            'email' => 'required|string|max:100|unique:students',
            'password' => 'sometimes|required|string|min:6',
            'image' => 'sometimes|required|mimes:jpg,jpeg,gif,png',
        ])->validate();

        $data['nivel'] = 'student';

        if($request->password){
            $data['password'] =  bcrypt($request->password);
        }

        // envia a imagem
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $file = $request->image->store('users','public');
            $data['image'] = $file;
        }

        if($this->user->create($data)) {
            return redirect('admin/students')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/students/create')->with('error', 'Erro ao inserir o registro!');
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
        $polos = $this->polo->all();
        $student = $this->student->find($id);
        if ($student) {
            return view('admin.students.show', ['student' => $student, 'polos' => $polos]);
        } else {
            return redirect('admin/students')->with('alert', 'Registro não encontrado!'); 
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $polos = $this->polo->all();
        $student = $this->user->find($id); // consulta a tabela de users
        if ($student) {
            return view('admin.students.edit', ['student' => $student, 'polos' => $polos]);
        } else {
            return redirect('admin/students')->with('alert', 'Registro não encontrado!'); 
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
        $data = $request->all(); // recebe os dados do form.
        $student = $this->user->find($id); // consulta a tabela de users

        if (!$student) {
            return redirect('admin/students')->with('alert', 'Registro não encontrado!');
        }

        if(!$data['password']):
            unset($data['password']);
        endif;

        Validator::make($data, [
            'polo_id' => 'required',
            'name' => 'required|string|max:100',
            'rg' => ['required','string','max:30',Rule::unique('students')->ignore($id)],
            'cpf' => ['required','string','max:15',Rule::unique('students')->ignore($id)],
            'phone' => 'required|string|max:16',
            'zip_code' => 'required|string|max:9',
            'address' => 'required|string|max:250',
            'district' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
            'local' => 'required|string',
            'email' => ['required','string','email','max:100',Rule::unique('students')->ignore($id)],
            'password' => 'sometimes|required|string|min:6',
            'image' => 'sometimes|required|mimes:jpg,jpeg,gif,png',
        ])->validate();

        if($request->password){
            $data['password'] =  bcrypt($request->password);
        }

        if($request->hasFile('image') && $request->file('image')->isValid()){
            if($student['image'] != null){
                if(Storage::exists($student['image'])) {
                    Storage::delete($student['image']);
                }
            }
            
            $new_file = $request->image->store('users','public');
            $data['image'] = $new_file;
        }

        if($student->update($data)) {
            return redirect('admin/students')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/students')->with('error', 'Erro ao alterado o registro!');
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
        $data = $this->user->find($id); // consulta a tabela users
        if($data->delete()) 
        {
            if(($data['image'] != null) && (Storage::exists($data['image']))){
                Storage::delete($data['image']);
            }
            return redirect('admin/students')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/students')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
