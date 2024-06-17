<?php

use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\UserTicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
#todo добавить префиксы
Route::controller(TicketController::class)
    ->group(function(){
        Route::get('/tickets', 'index')->name('tickets.index');
        Route::get('/tickets/{ticket}', 'show')->name('tickets.show');
        Route::post('/tickets', 'store')->name('tickets.store');
        Route::put('/tickets/{ticket}', 'update')->name('tickets.update');
        Route::patch('/tickets/{ticket}', 'update')->name('tickets.update');
        Route::delete('/tickets/{ticket}', 'destroy')->name('tickets.destroy');

    });

Route::controller(UserTicketController::class)
    ->group(function(){
        Route::get('/tickets/{ticket}/show-mates', 'showMates')->name('ticket.show_mates');
        Route::post('/tickets/{ticket}/add-mate', 'addMate')->name('tickets.add_mate');
        Route::patch('/tickets/{ticket}/mate', 'updateRole')->name('tickets.update_role');
        #todo возможно стоит заменить mate на mate_id
        Route::delete('/tickets/{ticket}/mate', 'destroy')->name('user_tickets.destroy');
    });