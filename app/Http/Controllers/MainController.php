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
            ->leftJoin('services', 'parking_spaces.id', '=', 'services.id_parking_space')
            ->select('parking_spaces.parking_space_number', DB::raw('MAX(services.service_type) AS service_type'), 
                DB::raw('MAX(services.driver_name) AS driver_name'), 'parking_spaces.status')
            ->orderBy('parking_spaces.parking_space_number')
            ->groupBy('parking_spaces.parking_space_number', 'parking_spaces.status')
            ->get();

            $numberServicesFinished = DB::table('services')
            ->where('services.status', '=', 'Finalizado')
            ->count();

            $numberServicesInProgress = DB::table('services')
            ->where('services.status', '=', 'Em andamento')
            ->count();

            $numberSpacesOccupied = DB::table('parking_spaces')
            ->where(function($query){
                $query->where('status', 'Ocupado')
                ->orWhere('status', 'Reservado');
            })->count();
            $number_spaces = DB::table('parking_spaces')->count();

            $numberCustomers = DB::table('customers')
            ->count();

            $numberReservations = DB::table('reservations')
            ->count();
            $numberActiveReservations = DB::table('reservations')
            ->where('active', '=', '1' )
            ->count();
            $numberNoActiveReservations = DB::table('reservations')
            ->where('active', '=', '0' )
            ->count();

            $numberSpacesBadge['Occupied'] = DB::table('parking_spaces')
            ->where('status', '=', 'Ocupado')
            ->count();
            $numberSpacesBadge['Reserved'] = DB::table('parking_spaces')
            ->where('status', '=', 'Reservado')
            ->count();
            $numberSpacesBadge['Liberated'] = DB::table('parking_spaces')
            ->where('status', '=', 'Liberado')
            ->count();

            $contents = [
                'numberSpacesOccupied' => $numberSpacesOccupied,
                'numberServicesInProgress' => $numberServicesInProgress,
                'numberServicesFinished' => $numberServicesFinished,
                'number_spaces' => $number_spaces,
                'parking_spaces' => $parking_spaces,
                'numberCustomers' => $numberCustomers,
                'numberReservations' => $numberReservations,
                'numberActiveReservations' => $numberActiveReservations,
                'numberNoActiveReservations' => $numberNoActiveReservations,
                'numberSpacesBadge' => $numberSpacesBadge,
                'title' => 'Página inicial',
                'view' => 'main',
            ];
            return view('master', compact('contents'));
        }else{
            return back()->withErrors(['message' => 'Acesso restrito!']);
        }
    }
}
