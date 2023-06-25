<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function user($id)
    {
        if(session()->has('user')){

            $user = DB::table('users')
            ->where('id', '=', $id)
            ->first();
            
            $contents = [
                'user' => $user,
                'view' => 'user',
                'title' => 'Usuário'
            ];
            return view('master', compact('contents'));
        }else{
            return back()->withErrors(['message' => 'Acesso restrito!']);
        } 
    }
}
