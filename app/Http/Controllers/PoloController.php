<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Polo;

class PoloController extends Controller
{
    private $polo;

    public function __construct(Polo $polo) {
        $this->polo = $polo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $polos = $this->polo->paginate(10);
        return view('admin.polos.index',['polos' => $polos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.polos.create');
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
            'title' => 'required|string|unique:polos|max:100',
            'phone' => 'required|string',
            'email' => 'required|string|unique:polos|max:100',
            'city' => 'required|string',
            'state' => 'required|string',
        ])->validate();

        if($this->polo->create($data)) {
            return redirect('admin/polos')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/polos/create')->with('error', 'Erro ao inserir o registro!');
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
        $polo = $this->polo->find($id);
        if (!$polo) {
            return redirect('admin/polos')->with('alert', 'Registro não encontrado.');
        }

        return view('admin.polos.edit',['polo' => $polo]);
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
        $polo = $this->polo->find($id);

        Validator::make($data, [
            'title' => ['required','string','max:100',Rule::unique('polos')->ignore($id)],
            'phone' => 'required|string',
            'email' => ['required','string','max:100',Rule::unique('polos')->ignore($id)],
            'city' => 'required|string',
            'state' => 'required|string',
        ])->validate();

        if($polo->update($data)) {
            return redirect('admin/polos')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/polos')->with('error', 'Erro ao inserir o registro!');
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
        $data = $this->polo->find($id);
        if($data->delete()) 
        {
            return redirect('admin/polos')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/polos')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
