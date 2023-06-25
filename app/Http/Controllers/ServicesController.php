<?php

namespace App\Http\Controllers;

use App\Mail\EmailSender;
use App\Models\Parking_spaces;
use App\Models\Services;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;


class ServicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Services
        public function services()
        {
            if(session()->has('user')){

                $services = DB::table('services')
                ->join('users', 'users.id', '=', 'services.id_user')
                ->join('parking_spaces', 'parking_spaces.id', '=', 'services.id_parking_space')
                ->join('customers', 'customers.id', '=', 'services.id_customer', 'left')
                ->whereDate('services.entry_time', today())
                ->select('services.*', 'customers.name AS customer_name', 'parking_spaces.parking_space_number AS space_number', 
                'parking_spaces.description AS space_description', 'users.name AS user_name')
                ->orderBy('services.created_at', 'asc')
                ->get();

                $contents = [
                    'view' => 'services',
                    'services' => $services,
                    'title' => 'Serviços'
                ];
                return view('master', compact('contents'));
            }else{
                return back()->withErrors(['message' => 'Acesso restrito!']);
            } 
        }
    //Services

    //Separated services
        public function separated_service()
        {
            if(session()->has('user')){

                $spaces = DB::table('parking_spaces')
                ->where('status', 'Liberado')
                ->get();

                $services = DB::table('services')
                ->join('users', 'users.id', '=', 'services.id_user')
                ->join('parking_spaces', 'parking_spaces.id', '=', 'services.id_parking_space')
                ->join('customers', 'customers.id', '=', 'services.id_customer', 'left')
                ->where('services.service_type', 'avulso')
                ->select('services.*', 'customers.name AS customer_name', 'parking_spaces.parking_space_number AS space_number', 
                'parking_spaces.description AS space_description', 'users.name AS user_name')
                ->orderBy('services.created_at', 'asc')
                ->get();

                $contents = [
                    'view' => 'separated_services',
                    'spaces' => $spaces,
                    'services' => $services,
                    'title' => 'Serviços avulso'
                ];
                return view('master', compact('contents'));
            }else{
                return back()->withErrors(['message' => 'Acesso restrito!']);
            }
        }

        public function AddSeparatedService(Request $request)
        {

            if(session()->has('user')){
                $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

                $service_code = null;
                while (!$service_code) {
                    $random_number = str_pad(mt_rand(1,99999), 5, '0', STR_PAD_LEFT);
                    $existing_service = Services::where('service_code', $random_number)->first();
                    if (!$existing_service) {
                        $service_code = $random_number;
                    }
                }

                $validatedData = $request->validate([
                    'id_user' => 'required|integer',
                    'id_parking_space' => 'required|integer',
                    'driver_name' => 'required|string|max:255',
                    'driving_license_number' => 'required|string|max:255',
                    'license_plate_number' => 'required|string|max:255',
                    'vehicle_brand' => 'required|string|max:50',
                    'vehicle_model' => 'required|string|max:50',
                    'vehicle_color' => 'required|string|max:50',
                    'driver_phone_number' => 'required|string|max:50',
                    'driver_email' => 'required|string|max:255',
                ]);
        
                $sanitizedData = filter_var_array($validatedData, FILTER_SANITIZE_STRING);

                $phone_number = $sanitizedData['driver_phone_number'];
                $escapade_phone_number = preg_replace('/[^0-9]/', '', $phone_number);

                $sanitizedData['service_type'] = 'avulso';
                $sanitizedData['service_code'] = $service_code;
                $sanitizedData['driver_phone_number'] = $escapade_phone_number;
                $date = new DateTime();
                $sanitizedData['entry_time'] = $date->format('Y-m-d H:i:s');
                $sanitizedData['status'] = 'Em andamento';

                DB::table('parking_spaces')
                ->where('id', $sanitizedData['id_parking_space'])
                ->update([
                    'status' => 'Ocupado',
                    'updated_at' =>  date("Y-m-d H:i:s")
                ]);

                $service = Services::create($sanitizedData);
                $service_id = $service->id;
                if ($request->input('receipt_email') === '1') {
                    Mail::to($sanitizedData['driver_email'])->send(new EmailSender($service_id));
                }    
                
                return redirect()->action([ServicesController::class, 'separated_service'])->with('success', 'Serviço adicionado.');

            }else{
                // Redireciona o usuário para outra view
                return redirect()->action([AdminController::class, 'logout']);
            }
        }
    //Separated services

    //Customer methods
        public function customer_service()
        {
            if(session()->has('user')){

                $spaces = DB::table('parking_spaces')
                ->where('status', 'Liberado')
                ->get();

                $services = DB::table('services')
                ->join('users', 'users.id', '=', 'services.id_user')
                ->join('parking_spaces', 'parking_spaces.id', '=', 'services.id_parking_space')
                ->join('customers', 'customers.id', '=', 'services.id_customer', 'left')
                ->where('services.service_type', 'cliente')
                ->select('services.*', 'customers.name AS customer_name', 'parking_spaces.parking_space_number AS space_number', 
                'parking_spaces.description AS space_description', 'users.name AS user_name')
                ->orderBy('services.created_at', 'asc')
                ->get();

                $customers = DB::table('customers')
                ->select('id', 'name')
                ->get();

                $contents = [
                    'view' => 'customer_services',
                    'spaces' => $spaces,
                    'services' => $services,
                    'customers' => $customers,
                    'title' => 'Serviços cliente'
                ];
                return view('master', compact('contents'));
            }else{
                return back()->withErrors(['message' => 'Acesso restrito!']);
            }
        }

        public function AddCustomerService(Request $request)
        {

            if(session()->has('user')){
                $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

                $service_code = null;
                while (!$service_code) {
                    $random_number = str_pad(mt_rand(1,99999), 5, '0', STR_PAD_LEFT);
                    $existing_service = Services::where('service_code', $random_number)->first();
                    if (!$existing_service) {
                        $service_code = $random_number;
                    }
                }

                $validatedData = $request->validate([
                    'id_user' => 'required|integer',
                    'id_customer' => 'required|integer',
                    'id_parking_space' => 'required|integer',
                    'driving_license_number' => 'required|string|max:255',
                    'license_plate_number' => 'required|string|max:255',
                    'vehicle_brand' => 'required|string|max:50',
                    'vehicle_model' => 'required|string|max:50',
                    'vehicle_color' => 'required|string|max:50',
                    'driver_phone_number' => 'required|string|max:50',
                    'driver_email' => 'required|string|max:255',
                ]);
        
                $sanitizedData = filter_var_array($validatedData, FILTER_SANITIZE_STRING);

                $customer =  DB::table('customers')
                ->select('name')
                ->where('id', $sanitizedData['id_customer'])
                ->first();

                $sanitizedData['driver_name'] = $customer->name;

                $phone_number = $sanitizedData['driver_phone_number'];
                $escapade_phone_number = preg_replace('/[^0-9]/', '', $phone_number);

                $sanitizedData['service_type'] = 'cliente';
                $sanitizedData['service_code'] = $service_code;
                $sanitizedData['driver_phone_number'] = $escapade_phone_number;
                $date = new DateTime();
                $sanitizedData['entry_time'] = $date->format('Y-m-d H:i:s');
                $sanitizedData['status'] = 'Em andamento';

                DB::table('parking_spaces')
                ->where('id', $sanitizedData['id_parking_space'])
                ->update([
                    'status' => 'Ocupado',
                    'updated_at' =>  date("Y-m-d H:i:s")
                ]);

                $service = Services::create($sanitizedData);
                $service_id = $service->id;
                if ($request->input('receipt_email') === '1') {
                    Mail::to($sanitizedData['driver_email'])->send(new EmailSender($service_id));
                }    
        
                
                
                return redirect()->action([ServicesController::class, 'customer_service'])->with('success', 'Serviço adicionado.');

            }else{
                // Redireciona o usuário para outra view
                return redirect()->action([AdminController::class, 'logout']);
            }
        }

        public function get_customer(Request $request)
        {

            if(session()->has('user')){
                $validatedData = $request->validate([
                    'id_customer' => 'required|integer',
                ]);
            
                
                $customer_data = DB::table('customers')
                ->select('id', 'driving_license_number', 'email', 'phone')
                ->where('id', $validatedData['id_customer'])
                ->first();

                $customer_vehicles = DB::table('customer_vehicles')
                ->select('id', 'model')
                ->where('id_customer', $customer_data->id)
                ->get()
                ->toArray();

                $data = array_merge(get_object_vars($customer_data), ['vehicles' => $customer_vehicles]);

                return response()->json($data);

            }else{
                return redirect()->action([AdminController::class, 'logout']);
            }
        }

        public function get_vehicle(Request $request)
        {
            if(session()->has('user')){
                $validatedData = $request->validate([
                    'id_vehicle' => 'required|integer',
                ]);
            
                
                $vehicle_data = DB::table('customer_vehicles')
                ->select('license_plate_number', 'brand', 'color')
                ->where('id', $validatedData['id_vehicle'])
                ->first();

                return response()->json($vehicle_data);

            }else{
                return redirect()->action([AdminController::class, 'logout']);
            }
        }                                                               
    //Customer methods

    //Both services
        public function finish_service(Request $request)
        { 

            if(session()->has('user')){
                $validatedData = $request->validate([
                    'id_service' => 'required|integer',
                ]);
                
                $service = DB::table('services')
                ->select('entry_time', 'service_type', 'id_parking_space')
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
                    if($diferencaEmMinutos < 30){
                        $service_value = 1.50;
                    }else{
                        $service_value = number_format(floatval(($hour_price/60)*$diferencaEmMinutos),2);
                    }
                
                }else{
                    
                    $hour_price  = 2.00;
                    if($diferencaEmMinutos < 30){
                        $service_value = 1.00;
                    }else{
                        $service_value = number_format(floatval(($hour_price/60)*$diferencaEmMinutos),2);
                    }
                }

                DB::table('services')
                ->where('id', $validatedData['id_service'])
                ->update([
                    'departure_time' => $dataAtual,
                    'value' => $service_value,
                    'status' => 'Finalizado',
                    'updated_at' =>  date("Y-m-d H:i:s")
                ]);

                $service = DB::table('services')
                ->select('departure_time', 'value', 'status', 'id_parking_space')
                ->where('id', $validatedData['id_service'])->first();

                DB::table('parking_spaces')
                ->where('id', $service->id_parking_space)
                ->update([
                    'status' => 'Liberado',
                    'updated_at' =>  date("Y-m-d H:i:s")
                ]);

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
    //Both services
}
