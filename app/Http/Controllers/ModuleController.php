<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Module;
use App\Models\Subject;

class ModuleController extends Controller
{
    private $module;
    private $subject;

    public function __construct(Module $module, Subject $subject ) {
        $this->module = $module;
        $this->subject = $subject;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index ($id) {

        $disciplina = $this->subject->find($id);
        if ($disciplina) {

            $subjects = $this->subject->all();
            $files = $this->module->where('category','file')->orderBy('id','DESC')->get();
            $videos = $this->module->where('category','movie')->orderBy('id','DESC')->get();
            
            $modules = $this->module->where('subject_id',$id)->where('category','module')->orderBy('id','DESC')->get();
            return view('admin.modules.index', [
                'modules' => $modules, 
                'subjects' => $subjects, 
                'disciplina' => $disciplina,
                'files' => $files,
                'videos' => $videos
            ]);
        }
        
        return redirect('admin/subjects')->with('alert', 'Disciplina e modulos não encontrado.');
    }

    public function search (Request $request) {

        $id = $request->subject_id;

        $disciplina = $this->subject->find($id);
        if ($disciplina) {

            $subjects = $this->subject->all();
            
            $modules = $this->module->where('subject_id',$id)->orderBy('id','DESC')->get();
            return view('admin.modules.search', ['modules' => $modules, 'subjects' => $subjects, 'disciplina' => $disciplina]);
        }
        
        return redirect('admin/subjects')->with('alert', 'Disciplina e modulos não encontrado.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $subject = $this->subject->find($id);
        if ($subject) {
            return view('admin.modules.create', ['subject' => $subject]);
        }
        
        return redirect('admin/subjects')->with('alert', 'Disciplina e modulos não encontrado.');
    }


    public function fileCreate($id)
    {
        $subject = $this->subject->find($id);
        if ($subject) {
            return view('admin.modules.fileCreate', ['subject' => $subject]);
        }
        
        return redirect('admin/subjects')->with('alert', 'Disciplina e modulos não encontrado.');
    }

    public function videoCreate($id)
    {
        $subject = $this->subject->find($id);
        if ($subject) {
            return view('admin.modules.videoCreate', ['subject' => $subject]);
        }
        
        return redirect('admin/subjects')->with('alert', 'Disciplina e modulos não encontrado.');
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
            'title' => 'required|string|unique:modules|max:200',
            'description' => 'required|string',
            'file' => 'sometimes|required|mimes:pdf,doc,docx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        // envia o arquivo
        if($request->hasFile('file') && $request->file('file')->isValid()){
            $file = $request->file->store('files','public');
            $data['file'] = $file;
        }

        if($this->module->create($data)) {
            return redirect('admin/subject/'.$data['subject_id'].'/modules')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/subject/'.$data['subject_id'].'/modules')->with('error', 'Erro ao inserir o registro!');
        }
    }

    public function videoStore(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'link' => 'required|string',
            'description' => 'required|string',
        ])->validate();

        $data['title'] = "Vídeo para estudo";
        $data['slug'] = "armazenamento-de-video";

        if($this->module->create($data)) {
            return redirect('admin/subject/'.$data['subject_id'].'/modules')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/subject/'.$data['subject_id'].'/modules')->with('error', 'Erro ao inserir o registro!');
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
        $module = $this->module->find($id);
        if ($module) {
            return view('admin.modules.edit', ['module' => $module]);
        } else {
            return redirect('admin/subject/'.$id.'/modules')->with('error','Registro não encontrado!');
        }
    }

    public function fileEdit($id)
    {
        $module = $this->module->find($id);
        if ($module) {
            return view('admin.modules.fileEdit', ['module' => $module]);
        } else {
            return redirect('admin/subject/'.$id.'/modules')->with('error','Registro não encontrado!');
        }
    }

    public function videoEdit($id)
    {
        $module = $this->module->find($id);
        if ($module) {
            return view('admin.modules.videoEdit', ['module' => $module]);
        } else {
            return redirect('admin/subject/'.$id.'/modules')->with('error','Registro não encontrado!');
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
        $module = $this->module->find($id);

        Validator::make($data, [
            'title' => ['required','string','max:200',Rule::unique('modules')->ignore($id)],
            'description' => 'required|string',
            'file' => 'sometimes|mimes:pdf,doc,docx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('file') && $request->file('file')->isValid()){
            if(($module['file'] != null) && (Storage::disk('public')->exists($module['file']))) {
                Storage::disk('public')->delete($module['file']);
            }
            
            $new_file = $request->file->store('files','public');
            $data['file'] = $new_file;
        }

        if($module->update($data)) {
            return redirect('admin/subject/'.$data['subject_id'].'/modules')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/subject/'.$data['subject_id'].'/modules')->with('error', 'Erro ao alterar o registro!');
        }
    }

    public function videoUpdate(Request $request, $id)
    {
        $data = $request->all();
        $module = $this->module->find($id);

        Validator::make($data, [
            'description' => 'required|string',
            'link' => 'required|string',
        ])->validate();
        
        $data['title'] = "Vídeo para estudo";
        $data['slug'] = "armazenamento-de-video";

        if($module->update($data)) {
            return redirect('admin/subject/'.$data['subject_id'].'/modules')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/subject/'.$data['subject_id'].'/modules')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->module->find($id);

        if(($data['file'] != null) && (Storage::disk('public')->exists($data['file']))) {
            Storage::disk('public')->delete($data['file']);
        }

        if($data->delete()) 
        {
            return redirect('admin/subject/'.$data['subject_id'].'/modules')->with('success', 'Registro excluir com sucesso!');
        } else {
            return redirect('admin/subject/'.$data['subject_id'].'/modules')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
