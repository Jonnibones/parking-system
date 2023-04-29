<?php

namespace App\Http\Controllers;

use App\Models\Parking_spaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    public function index()
    {
        if(session()->has('user')){

            $customers = DB::table('customers')
            ->select('customers.*')
            ->orderBy('customers.name', 'asc')
            ->get();

            $contents = [
                'view' => 'customers',
                'customers' => $customers,
            ];
            return view('master', compact('contents'));
        }else{
            return back()->withErrors(['message' => 'Acesso restrito!']);
        } 
    }
}
