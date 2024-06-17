<?php

namespace App\Http\Controllers\Api;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserTicket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(){
        #todo только член тикета может видеть тикет
        #todo так несовсем правильно (потом надо исправить)
        return Ticket::query()
            ->select(['id', 'title', 'body', 'status', 'finish_at'])
            ->get();
    }

    #Заменю $id на Ticket $ticket чтобы ларавел заранее находил объект в бд
    public function show(Ticket $ticket){
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

    public function store(StoreTicketRequest $request){
        #todo изменить когда сделаю авторизацию
        auth()->login(User::first());
        
        $ticket = Ticket::create([
            'title' => $request->str('title'),
            'body' => $request->str('body'),
            'status' => $request->enum('status', TicketStatus::class),
            'finish_at' => $request->str('finish_at'), #todo когда поле==NULL поле не должно передаваться
        ]);
        
        auth()->user()->userticket()->create([
            'ticket_id' => $ticket->id,
            'role' =>  UserRole::Creator
        ]);
 
        return response()->json([
            'ticket_id' => $ticket->id
        ], 201);
    }

    public function update(Request $request, Ticket $ticket){
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

    public function destroy(Ticket $ticket){
        #Удалять может только creator
        $ticket->delete();
        return response()->json(["status" => "success"], 200);
    }
}
