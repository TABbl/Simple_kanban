<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserTicket;
use Illuminate\Http\Request;

class UserTicketController extends Controller
{
    public function showMates(Ticket $ticket){
        #todo добавить обработку 404
        #todo только пользователи тикета могут видеть инф-ю о тикете
        return [
            'title' => $ticket->title,
            'mates' => $ticket->userticket->map(fn(UserTicket $userticket)=>[
                'name' => $userticket->user->name,
                'role' => $userticket->role
            ])
        ];
    }

    public function addMate(Request $request, Ticket $ticket){
        #todo добавить обработку ошибок (или возможно можно сделать для этого валидацию)
        #todo изменить когда сделаю авторизацию
        auth()->login(User::first());
        #todo переписать эту страшилку
        $role = UserTicket::where('user_id', auth()->user()->id)->where('ticket_id', $ticket->id)->first('role');
        $role = json_decode($role, true);

        if($role['role'] == 'creator'){
            $add_mate = UserTicket::create([
                'user_id' => $request->user_id, #todo создатель передает name напарника, а не его id
                'ticket_id' => $ticket->id,
                'role' => $request->role
            ]);
            return $add_mate;
        }
        else{
            return response("Only creator can add mate into ticket", 403);
        }
    }

    public function updateRole(Request $request, Ticket $ticket){
        #todo переписать этот ужас
        $user_ticket = UserTicket::where('user_id', $request->integer('user_id'))->where('ticket_id', $ticket->id)->first();
        $user_ticket->update([
            'role' => $request->enum('role', UserRole::class)
        ]);
    }

    public function destroy(Request $request, Ticket $ticket){
        #todo добавить валидацию
        #удалять может только creator
        UserTicket::where('user_id', $request->integer('user_id'))
            ->where('ticket_id', $ticket->id)
            ->delete();
        return response()->json(["status" => "success"], 200);
    }
}
