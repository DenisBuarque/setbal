<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Contract;

class ContractController extends Controller
{
    private $contract;

    public function __construct(Contract $contract) 
    {
        $this->contract = $contract;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->contract->query();

        if (isset($request->title)) {
            $columns = ['title'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $request->title . '%');
            }
        }

        $contracts = $query->orderBY('id','DESC')->paginate(10);

        return view('admin.contracts.index', [
            'contracts' => $contracts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.contracts.create');
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
            'title' => 'required|string|max:200',
            'description' => 'required|string',
        ])->validate();

        if($this->contract->create($data)) {
            return redirect('admin/contracts')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/contracts/create')->with('error', 'Erro ao inserir o registro!');
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
        $contract = $this->contract->find($id);
        if ($contract) {
            return view('admin.contracts.edit',['contract' => $contract]);
        }

        return redirect('admin/contracts')->with('alert', 'Registro não encontrado!');
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
        $contract = $this->contract->find($id);

        if (!$contract) {
            return redirect('admin/contracts')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'title' => 'required|string|max:200',
            'description' => 'required|string',
        ])->validate();

        if($contract->update($data)) {
            return redirect('admin/contracts')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/contracts')->with('error', 'Erro ao alterado o registro!');
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
        $data = $this->contract->find($id);
        if($data->delete()) 
        {
            return redirect('admin/contracts')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/contracts')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
