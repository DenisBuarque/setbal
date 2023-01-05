<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\Polo;

class CourseController extends Controller
{
    private $course;
    private $polo;

    public function __construct(Course $course, Polo $polo) 
    {
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
        $query = $this->course->query(); // consulta a tabela courses
        
        $search = $request->search;
        $search_polo = $request->polo;

        // se o campo busca estiver preenchido
        if (isset($search)) {
            $columns = ['title','type','status'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $search . '%');
            }
        }

        if (isset($search_polo)) {
            $query->where('polo_id', $search_polo);
        }

        $polos = $this->polo->all();

        $courses = $query->orderBy('id','DESC')->paginate(10);

        return view('admin.courses.index',[
            'courses' => $courses,
            'polos' => $polos,
            'search' => $search,
            'search_polo' => $search_polo
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
        return view('admin.courses.create',['polos' => $polos]);
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
            'title' => 'required|string|unique:courses|max:100',
            'duration' => 'required|string|max:30',
            'description' => 'required'
            //'file' => 'required|mimes:pdf,doc,docx,xlsx,xlsm,xlsb,xltx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        // envia a imagem
        if($request->hasFile('photo') && $request->file('photo')->isValid()){
            $file = $request->photo->store('courses','public');
            $data['photo'] = $file;
        }

        if($this->course->create($data)) {
            return redirect('admin/courses')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/courses/create')->with('error', 'Erro ao inserir o registro!');
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
        $polos = $this->polo->all();
        $cource = $this->course->find($id);
        if (!$cource) {
            return redirect('admin/courses')->with('alert', 'Registro não encontrado.');
        }

        return view('admin.courses.edit',['course' => $cource, 'polos' => $polos]);
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
        $course = $this->course->find($id);

        Validator::make($data, [
            'title' => ['required','string','max:100',Rule::unique('courses')->ignore($id)],
            'duration' => 'required|string|max:30',
            'description' => 'required'
            //'file' => 'required|mimes:pdf,doc,docx,xlsx,xlsm,xlsb,xltx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        //imagem
        if($request->hasFile('photo') && $request->file('photo')->isValid()){
            
            if(($course['photo'] != null) && (Storage::disk('public')->exists($course['photo']))){
                Storage::disk('public')->delete($course['photo']);
            } 

            $new_file = $request->photo->store('courses','public');
            $data['photo'] = $new_file;
        }

        if($course->update($data)) {
            return redirect('admin/courses')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/courses')->with('error', 'Erro ao inserir o registro!');
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
        $data = $this->course->find($id);
        if($data->delete()) 
        {
            if(($data['photo'] != null) && (Storage::disk('public')->exists($data['photo']))){
                Storage::disk('public')->delete($data['photo']);
            } 

            return redirect('admin/courses')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/courses')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
