<?php

namespace App\Http\Controllers;

use App\Models\Services;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ServicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

            $services = DB::table('services')
            ->join('users', 'users.id', '=', 'services.id_user')
            ->join('parking_spaces', 'parking_spaces.id', '=', 'services.id_parking_space')
            ->join('customers', 'customers.id', '=', 'services.id_customer', 'left')
            ->select('services.*', 'customers.name AS customer_name', 'parking_spaces.parking_space_number AS space_number', 
            'parking_spaces.description AS space_description', 'users.name AS user_name')
            ->orderBy('services.created_at', 'asc')
            ->get();

            $contents = [
                'view' => 'separated_services',
                'spaces' => $spaces,
                'services' => $services,
            ];
            return view('master', compact('contents'));
        }else{
            return back()->withErrors(['message' => 'Acesso restrito!']);
        }
    }

    public function AddSeparatedService(Request $request){

        if(session()->has('user')){
            $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

            $validatedData = $request->validate([
                'id_user' => 'required|integer',
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
            $date = new DateTime();
            $sanitizedData['entry_time'] = $date->format('Y-m-d H:i:s');
            $sanitizedData['status'] = 'Em andamento';
    
            Services::create($sanitizedData);
        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }

    public function finish_service(Request $request){ 

        if(session()->has('user')){
            $validatedData = $request->validate([
                'id_service' => 'required|integer',
            ]);
        
            
            $service = DB::table('services')
            ->select('entry_time', 'service_type')
            ->where('id', $validatedData['id_service'])
            ->first();

            $data = Carbon::parse($service->entry_time);
            // pega a data atual
            $dataAtual = Carbon::now();
            // calcula a diferença em minutos
            $diferencaEmMinutos = $data->diffInMinutes($dataAtual);// pega a data atual
            $dataAtual = Carbon::now();
            // calcula a diferença em minutos
            $diferencaEmMinutos = $data->diffInMinutes($dataAtual);

            if($service->service_type == 'avulso'){
                $hour_price  = 3.00;
                $service_value = number_format(floatval(($hour_price/60)*$diferencaEmMinutos),2) ;
            }else{
                $hour_price  = 2.00;
                $service_value = number_format(floatval(($hour_price/60)*$diferencaEmMinutos),2) ;
            }

            DB::table('services')
            ->where('id', $validatedData['id_service'])
            ->update([
                'departure_time' => $dataAtual,
                'value' => $service_value,
                'status' => 'Finalizado',
                'updated_at' => $dataAtual,
            ]);

            $service = DB::table('services')
            ->select('departure_time', 'value', 'status')
            ->where('id', $validatedData['id_service'])->first();

            $data = [
                'departure_time' => date('d-m-Y H:i:s', strtotime($service->departure_time)),
                'value' => $service->value,
                'status' => $service->status,
            ];

            return response()->json($data);
        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }
}
