<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\eMessage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function store(Request $req){
       eMessage::insert([
        'sender_id' => $req->sender_id,
        'group_id' => $req->group_id,
        'text_message' => $req->text_message
       ]);

    //    return redirect('/dashboard/{id}')->with('success','Message Sent');
         return back()->withInput();
    }
    
}
