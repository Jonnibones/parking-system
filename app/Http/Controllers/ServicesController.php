<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ServicesController extends Controller
{
    public function separated_service()
    {
        if(session()->has('user')){
            
            $spaces = DB::table('parking_spaces')
            ->whereNotExists(function($query){
                $query->select(DB::raw(1))
                ->from('services')
                ->whereColumn('services.id_parking_space', 'parking_spaces.id');
            })
            ->get();

            $contents = [
                'view' => 'separated_services',
                'spaces' => $spaces
            ];
            return view('master', compact('contents'));
        }else{
            return back()->withErrors(['message' => 'Acesso restrito!']);
        }
    }

    public function AddSeparatedService(Request $request){

        $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

        $validatedData = $request->validate([
            'id_parking_space' => 'required|integer',
            'driver_name' => 'required|string|max:255',
            'driving_license_number' => 'required|string|max:255',
            'license_plate_number' => 'required|string|max:255',
            'vehicle_brand' => 'required|string|max:50',
            'vehicle_model' => 'required|string|max:50',
            'vehicle_color' => 'required|string|max:50',
        ]);
    
        $sanitizedData = filter_var_array($validatedData, FILTER_SANITIZE_STRING);
    
        $sanitizedData['service_type'] = 'avulso';
    
        Services::create($sanitizedData);
    }
}
