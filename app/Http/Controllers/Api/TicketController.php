<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserTicket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct() {
        
        $this->middleware('auth:sanctum')
            ->only(['index', 'store', 'show', 'update', 'destroy']);
    }

    public function index()
    {
        #todo только член тикета может видеть тикет
        #todo так несовсем правильно (потом надо исправить)
        return Ticket::query()
            ->select(['id', 'title', 'body', 'status', 'finish_at'])
            ->get();
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'title' => $request->str('title'),
            'body' => $request->str('body'),
            'status' => $request->enum('status', TicketStatus::class),
            'finish_at' => $request->input('finish_at'),
        ]);
        
        auth()->user()->userticket()->create([
            'ticket_id' => $ticket->id,
            'role' =>  UserRole::Creator
        ]);
 
        return response()->json([
            'ticket_id' => $ticket->id
        ], 201);
    }

    public function show(Ticket $ticket)
    {
        #todo только член тикета может видеть тикет
        #todo добавить обработку 404
        #Middleware?
        return [
            'id' => $ticket->id,
            'title' => $ticket->title,
            'body' => $ticket->body,
            'status' => $ticket->status,
            'finish_at' => $ticket->finish_at,
            'users' => $ticket->userticket->map(fn(UserTicket $userticket) => [
                'id' => $userticket->id,
                'user_name' => $userticket->user->name,
                'role' => $userticket->role,
            ]),
        ];
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($request->method() == 'PUT'){
            $ticket->update([
                'title' => $request->str('title'),
                'body' => $request->input('body'),
                'status' => $request->enum('status', TicketStatus::class),
                'finish_at' => $request->str('finish_at'),
            ]);

        }else{
            #todo использовать DTO
            $data = [];

            if ($request->has('title')){
                $data['title'] = $request->str('title');
            }
            if ($request->has('body')){
                $data['body'] = $request->input('body');
            }
            if ($request->has('status')){
                $data['status'] = $request->enum('status', TicketStatus::class);
            }
            if ($request->has('finish_at')){
                $data['finish_at'] = $request->str('finish_at');
            }

            $ticket->update($data);
        }
    }

    public function destroy(Ticket $ticket)
    {
        #todo Удалять может только creator
        $ticket->delete();
        return response()->json(["status" => "success"], 200);
    }
}
