<?php

use App\Events\PresenceEvent;
use App\Events\PrivateEvent;
use App\Events\PublicEvent;
use App\Models\Group;
use App\Models\eMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | contains the "web" middleware group. Now create something great! | */


Route::get('/', function () {
    return view('welcome');
});

Route::post('/fireEvent', function (Request $request) {

    PublicEvent::dispatch($request->color);
})->name('fire.public.event');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get(
        '/private/fireEvent',
        function () {
            // faking file upload  
            sleep(3);
            PrivateEvent::dispatch('Your cv has been uploaded');
        }
        )->name('fire.private.event');


        Route::get('/dashboard', function () {
            $group = Group::where('id', auth()->user()->group_id)->first();
            return view('dashboard', compact('group'));
        }
        )->name('dashboard');

        Route::get('/dashboard/{group}', function (Request $request, Group $group) {
            $sender_details = eMessage::with(['eMessageData'])->where('group_id', auth()->user()->group_id)->get();
            //event(new \App\Events\PresenceEvent());
            abort_unless($request->user()->canJoinGroup($group->id), 401);
            return view('group', compact('group','sender_details'));
        }
        )->name('group');
 
        Route::get('/presence/fireEvent/{message}', fn() => PresenceEvent::dispatch())->name('fire.presence.event');

        Route::post('/store','App\Http\Controllers\UserController@store');
       
    });
require __DIR__ . '/auth.php';
