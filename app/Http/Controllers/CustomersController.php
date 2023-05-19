<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\CustomerVehicles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    public function index()
    {
        if(session()->has('user')){

            $customers = DB::table('customers')
            ->select('customers.*', 'customer_vehicles.id AS id_vehicle', 'customer_vehicles.model')
            ->orderBy('customers.name', 'asc')
            ->leftJoin('customer_vehicles', 'customer_vehicles.id_customer', '=', 'customers.id')
            ->get();

            $modifiedCustomers = array();
            foreach ($customers as $customer) {
                $customerId = $customer->id;
                if (!isset($modifiedCustomers[$customerId])) {
                    $modifiedCustomers[$customerId] = (array) $customer;
                    $modifiedCustomers[$customerId]['vehicles'] = array();
                }

                if (!is_null($customer->id_vehicle)) {
                    $vehicle = array(
                        'id_vehicle' => $customer->id_vehicle,
                        'model' => $customer->model
                    );
                    $modifiedCustomers[$customerId]['vehicles'][] = $vehicle;
                }
            }

            $modifiedCustomers = array_values($modifiedCustomers); //die(var_dump($modifiedCustomers));

            $contents = [
                'view' => 'customers',
                'customers' => $modifiedCustomers,
            ];
            return view('master', compact('contents'));
        }else{
            return back()->withErrors(['message' => 'Acesso restrito!']);
        } 
    }

    public function AddCustomer(Request $request)
    {
        if(session()->has('user')){
            $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'driving_license_number' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'phone' => 'required|string|max:50',
                'address' => 'required|string|max:255',
                'license_plate_number' => 'required|string|max:50',
                'model' => 'required|string|max:50',
                'brand' => 'required|string|max:50',
                'color' => 'required|string|max:50'
            ]);
            $sanitizedData = filter_var_array($validatedData, FILTER_SANITIZE_STRING);
            $phone_number = $sanitizedData['phone'];
            $escapade_phone_number = preg_replace('/[^0-9]/', '', $phone_number);
            $sanitizedData['phone'] = $escapade_phone_number;

            $newCustomer = Customers::create($sanitizedData); 
            $lastInsertedId = $newCustomer->id;

            
            $validatedData = $request->validate([
                'license_plate_number' => 'required|string|max:50',
                'model' => 'required|string|max:50',
                'brand' => 'required|string|max:50',
                'color' => 'required|string|max:50'
            ]);
            $sanitizedData = filter_var_array($validatedData, FILTER_SANITIZE_STRING);
            $sanitizedData['id_customer'] = $lastInsertedId;
            CustomerVehicles::create($sanitizedData);
    
            return redirect()->action([CustomersController::class, 'index'])->with('success', 'Cliente adicionado.');

        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }

    public function DeleteCustomer($id)
    {
        $customer = Customers::find($id);
        $customer->delete();
        return redirect()->action([CustomersController::class, 'index'])->with('success', 'Cliente deletado');
    }

    public function DeleteCustomers(Request $request){

        if(session()->has('user')){
            $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

            $ids_customers  = explode(',',$request->input('values'));
            array_pop($ids_customers);

            foreach($ids_customers as $id)
            {
                Customers::destroy($id);
            }
            
            return redirect()->action([CustomersController::class, 'index'])->with('success', 'Clientes deletados');

        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }  
    
    public function UpdateCustomer(Request $request)
    {
        if(session()->has('user')){
            $validatedData = $request->validate([
                'id_customer' => 'required|integer',
                'name' => 'required|string',
                'license' => 'required|string',
                'email' => 'required|string',
                'phone' => 'required|string',
                'address' => 'required|string',
            ]);
        
            
            $data = DB::table('customers')
            ->where('id', $validatedData['id_customer'])
            ->update([
                'name' => $validatedData['name'],
                'driving_license_number' => $validatedData['license'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
            ]);

            return response()->json($data);
        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }

    public function customers_vehicles($id = null)
    {
        if(session()->has('user')){

            $vehicles = DB::table('customer_vehicles')
            ->select('customer_vehicles.*', 'customers.id AS customer_id', 'customers.name AS customer')
            ->orderBy('customer_vehicles.model', 'asc')
            ->leftJoin('customers', 'customers.id', '=', 'customer_vehicles.id_customer')
            ->get();

            $customer = DB::table('customers')
            ->select('customers.*')
            ->where('customers.id', $id)
            ->orderBy('customers.name', 'asc')
            ->first();

            $customers = DB::table('customers')
                ->select('customers.*')
                ->orderBy('customers.name', 'asc')
                ->get();

            $contents = [
                'view' => 'customers_vehicles',
                'vehicles' => $vehicles,
                'customer' => $customer,
                'customers' => $customers,
                'id' => $id
            ];
            return view('master', compact('contents'));
        }else{
            return back()->withErrors(['message' => 'Acesso restrito!']);
        } 
    }

    public function DeleteVehicle($id)
    {
        $vehicle = CustomerVehicles::find($id);
        $vehicle->delete();
        return redirect()->action([CustomersController::class, 'customers_vehicles'])->with('success', 'Veículo deletado');
    }

    public function DeleteVehicles(Request $request){

        if(session()->has('user')){
            $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

            $ids_vehicles  = explode(',',$request->input('values'));
            array_pop($ids_vehicles);

            foreach($ids_vehicles as $id)
            {
                CustomerVehicles::destroy($id);
            }
            
            return redirect()->action([CustomersController::class, 'customers_vehicles'])->with('success', 'Veículos deletados');

        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    } 

    public function updateVehicle(Request $request){
        if(session()->has('user')){
            $validatedData = $request->validate([
                'id_vehicle' => 'required|integer',
                'model' => 'required|string',
                'brand' => 'required|string',
                'color' => 'required|string',
                'plate' => 'required|string',
            ]);
        
            
            $data = DB::table('customer_vehicles')
            ->where('id', $validatedData['id_vehicle'])
            ->update([
                'model' => $validatedData['model'],
                'brand' => $validatedData['brand'],
                'color' => $validatedData['color'],
                'license_plate_number' => $validatedData['plate'],
            ]);

            return response()->json($data);
        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }
}
