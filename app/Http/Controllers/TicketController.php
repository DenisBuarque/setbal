<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\User;

class TicketController extends Controller
{
    private $ticket;
    private $user;

    public function __construct(Ticket $ticket, User $user) 
    {
        $this->ticket = $ticket;
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ticket_total = $this->ticket->all();
        $ticket_open = $this->ticket->where('status','open')->get();
        $ticket_pending = $this->ticket->where('status','pending')->get();
        $ticket_close = $this->ticket->where('status','close')->get();
        
        $query = $this->ticket->query();

        $search = $request->user;
        $search2 = $request->status;

        if (isset($search)) {
            $query->where('user_id', $search);
        }

        if (isset($search2)) {
            $query->where('status', $search2);
        }

        $users = DB::table('users')
                    ->where('users.nivel','student')
                    ->where('users.active','ativo')
                    ->join('inscriptions', 'users.id', '=', 'inscriptions.user_id')
                    ->where('inscriptions.status','pago')
                    ->select(['users.*'])
                    ->get();

        $tickets = $query->orderBY('id','DESC')->paginate(10);

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'users' => $users,
            'ticket_total' => $ticket_total,
            'ticket_open' => $ticket_open,
            'ticket_pending' => $ticket_pending,
            'ticket_close' => $ticket_close,
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
        return view('admin.tickets.create');
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
            'user_id' => 'required',
            'subject' => 'required|string',
            'description' => 'required|string',
        ])->validate();

        if($this->ticket->create($data)) {
            return redirect('admin/tickets')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/tickets/create')->with('error', 'Erro ao inserir o registro!');
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
        $ticket = $this->ticket->find($id);
        if ($ticket) {
            return view('admin.tickets.edit',['ticket' => $ticket]);
        }

        return redirect('admin/tickets')->with('alert', 'Registro não encontrado!');
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
        $ticket = $this->ticket->find($id);

        if (!$ticket) {
            return redirect('admin/tickets')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'subject' => 'required|string',
            'description' => 'required|string',
        ])->validate();

        if($ticket->update($data)) {
            return redirect('admin/tickets')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/tickets')->with('error', 'Erro ao alterado o registro!');
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
        $data = $this->ticket->find($id);
        if($data->delete()) 
        {
            return redirect('admin/tickets')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/tickets')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
