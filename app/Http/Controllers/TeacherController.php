<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;

class TeacherController extends Controller
{
    private $user;
    private $course;
    private $subject;

    public function __construct(User $user, Course $course, Subject $subject) 
    {
        $this->user = $user;
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
        $query = $this->user->query(); // consulta a tabela de users

        $search1 = $request->course;
        $search2 = $request->search;

        if (isset($search1)) {
            $query->where('course_id',$search1);
        }

        if (isset($search2)) {
            $columns = ['name','local','active'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $search2 . '%');
            }
        }

        $courses = $this->course->all();

        $teachers = $query->where('nivel','=','teacher')->orderBY('id','DESC')->paginate(10);

        return view('admin.teachers.index', [
            'teachers' => $teachers,
            'courses' => $courses,
            'search1' => $search1,
            'search2' => $search2
        ]);
    }

    // lista as disciplinas pelo id do curso
    public function list ($id) 
    {
        $subjects = $this->subject->where('course_id', '=', $id)->get();
        return view('admin.teachers.search',['subjects' => $subjects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = $this->course->all();
        return view('admin.teachers.create',['courses' => $courses]);
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
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email|unique:users|max:100',
            'password' => 'sometimes|required|string|min:6'
        ])->validate();

        $data['nivel'] = 'teacher';

        if($request->password){
            $data['password'] =  bcrypt($request->password);
        }

        // envia a imagem
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $file = $request->image->store('users','public');
            $data['image'] = $file;
        }

        if($this->user->create($data)) {
            return redirect('admin/teachers')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/teachers/create')->with('error', 'Erro ao inserir o registro!');
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
        
        $teacher = $this->user->find($id); // consulta a tabela de usuários
        if ($teacher) {
            return view('admin.teachers.edit',['teacher' => $teacher, 'courses' => $courses, 'subjects' => $subjects]);
        }

        return redirect('admin/teachers')->with('alert', 'Registro não encontrado!');
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
        
        $data = $request->all(); // pega os dados do formulario
        $teacher = $this->user->find($id); // consulta a tabela users

        if (!$teacher) {
            return redirect('admin/teachers')->with('alert', 'Registro não encontrado!');
        }

        if(!$data['password']):
            unset($data['password']);
        endif;

        Validator::make($data, [
            'course_id' => 'required',
            'subject_id' => 'required',
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'email' => ['required','string','email','max:100',Rule::unique('users')->ignore($id)],
            'password' => 'sometimes|required|string|min:6'
        ])->validate();

        if($request->password){
            $data['password'] =  bcrypt($request->password);
        }

        if($request->hasFile('image') && $request->file('image')->isValid()){
            if($teacher['image'] != null){
                if(Storage::exists($teacher['image'])) {
                    Storage::delete($teacher['image']);
                }
            }
            
            $new_file = $request->image->store('users','public');
            $data['image'] = $new_file;
        }

        if($teacher->update($data)) {
            return redirect('admin/teachers')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/teachers')->with('error', 'Erro ao alterado o registro!');
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
        $data = $this->user->find($id);
        if($data->delete()) 
        {
            if(($data['image'] != null) && (Storage::exists($data['image']))){
                Storage::delete($data['image']);
            }
            return redirect('admin/teachers')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/teachers')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
