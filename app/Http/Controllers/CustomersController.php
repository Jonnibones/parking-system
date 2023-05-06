<?php

namespace App\Http\Controllers;

use App\Models\Customers;
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
            ]);
    
            $sanitizedData = filter_var_array($validatedData, FILTER_SANITIZE_STRING);

            $phone_number = $sanitizedData['phone'];
            $escapade_phone_number = preg_replace('/[^0-9]/', '', $phone_number);
            $sanitizedData['phone'] = $escapade_phone_number;

            Customers::create($sanitizedData);  
    
            return redirect()->action([CustomersController::class, 'index'])->with('success', 'Cliente adicionado.');

        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }

    public function DeleteCustomer($id)
    {
        $space = Customers::find($id);
        $space->delete();
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

}
