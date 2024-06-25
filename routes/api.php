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

Route::controller(TicketController::class)
    ->prefix('/tickets')
    ->group(function(){
        Route::get('', 'index')->name('tickets.index');
        Route::get('/{ticket}', 'show')->name('tickets.show');
        Route::post('', 'store')->name('tickets.store');
        Route::put('/{ticket}', 'update')->name('tickets.update');
        Route::patch('/{ticket}', 'update')->name('tickets.update');
        Route::delete('/{ticket}', 'destroy')->name('tickets.destroy');

    });

Route::controller(UserTicketController::class)
    ->prefix('tickets')
    ->group(function(){
        Route::get('/{ticket}/show-mates', 'showMates')->name('ticket.show_mates');
        Route::post('/{ticket}/add-mate', 'addMate')->name('tickets.add_mate');
        Route::patch('/{ticket}/mate', 'updateRole')->name('tickets.update_role');
        #todo возможно стоит заменить mate на mate_id
        Route::delete('/{ticket}/mate', 'destroyMate')->name('tickets.destroy_mate');
    });