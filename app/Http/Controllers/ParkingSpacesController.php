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

    public function updateSpace(Request  $request)
    {
        if(session()->has('user')){
            $validatedData = $request->validate([
                'id_space' => 'required|integer',
                'description' => 'required|string',
            ]);
        
            
            $data = DB::table('parking_spaces')
            ->where('id', $validatedData['id_space'])
            ->update([
                'description' => $validatedData['description']
            ]);

            return response()->json($data);
        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }

    public function DeleteParkingSpace($id)
    {
        $space = Parking_spaces::find($id);
        $space->delete();
        return redirect()->action([ParkingSpacesController::class, 'parking_spaces'])->with('success', 'Vaga deletada'); 
    }

    
    public function DeleteParkingSpaces(Request $request){

        if(session()->has('user')){
            $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

            $ids_spaces  = explode(',',$request->input('values'));
            array_pop($ids_spaces);

            foreach($ids_spaces as $id)
            {
                Parking_spaces::destroy($id);
            }
            
            return redirect()->action([ParkingSpacesController::class, 'parking_spaces'])->with('success', 'Vagas deletadas');

        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }   

}
