<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        if(session()->has('user')){

            $parking_spaces = DB::table('parking_spaces')
            ->leftJoin('services','parking_spaces.id', '=', 'services.id_parking_space')
            ->select('parking_spaces.parking_space_number', 'services.status AS ocuppied')
            ->orderBy('parking_spaces.parking_space_number')
            ->get();

            $number_services = DB::table('services')->count();
            $number_spaces = DB::table('parking_spaces')->count();

            $contents = [
                'number_services' => $number_services,
                'number_spaces' => $number_spaces,
                'parking_spaces' => $parking_spaces,
                'view' => 'main',
            ];
            return view('master', compact('contents'));
        }else{
            return back()->withErrors(['message' => 'Acesso restrito!']);
        }
    }
}
