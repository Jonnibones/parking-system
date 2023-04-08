<?php

namespace App\Http\Controllers;

use App\Models\Parking_spaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkingSpacesController extends Controller
{
    public function parking_spaces()
    {
        if(session()->has('user')){

            $parking_spaces = DB::table('parking_spaces')
            ->select('parking_spaces.*')
            ->orderBy('parking_spaces.parking_space_number', 'asc')
            ->get();

            $contents = [
                'view' => 'parking_spaces',
                'parking_spaces' => $parking_spaces,
            ];
            return view('master', compact('contents'));
        }else{
            return back()->withErrors(['message' => 'Acesso restrito!']);
        } 
    }

    public function AddParkingSpace(Request $request){

        if(session()->has('user')){
            $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

            $number_spaces = $request->input('number_spaces'); 

            for($i = 1; $i <= $number_spaces;  $i++){
                $validatedData = $request->validate([
                    'parking_space_number'.$i => 'required|integer',
                    'description'.$i => 'required|string|max:255',
                ]);
                
                $validatedData['parking_space_number'] = $validatedData['parking_space_number'.$i];
                $validatedData['description'] = $validatedData['description'.$i];
                unset($validatedData['parking_space_number'.$i]);
                unset($validatedData['description'.$i]);
                
                $parkingSpace = Parking_spaces::create($validatedData);
            }
            
            return redirect()->action([ParkingSpacesController::class, 'parking_spaces'])->with('success', 'Vagas adicionadas');

        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }
}
