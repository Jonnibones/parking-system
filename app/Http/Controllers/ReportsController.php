<?php

namespace App\Http\Controllers;

use App\Models\Reservations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportsController extends Controller
{
    public function index()
    {
        if(session()->has('user')){

            $contents = [
                'view' => 'reports'
            ];
            return view('master', compact('contents'));
        }else{
            return back()->withErrors(['message' => 'Acesso restrito!']);
        } 
    }

    /*
    public function AddReservation(Request $request){
        if(session()->has('user')){
            $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

            $validatedData = $request->validate([
                'id_customer' => 'required|integer',
                'id_parking_space' => 'required|integer',
            ]);
            $sanitizedData = filter_var_array($validatedData, FILTER_SANITIZE_STRING);

            Reservations::create($sanitizedData); 

            DB::table('parking_spaces')
            ->where('id', $sanitizedData['id_parking_space'])
            ->update([
                'status' => 'Reservado',
                'updated_at' =>  date("Y-m-d H:i:s")
            ]);
    
            return redirect()->action([ReservationsController::class, 'index'])->with('success', 'Reserva criada.');

        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }

    
    public function DeleteReservations(Request $request)
    {
        if(session()->has('user')){
            $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

            $ids_Reservations  = explode(',',$request->input('values'));
            array_pop($ids_Reservations);

            foreach($ids_Reservations as $id)
            {

                $reservation = DB::table('reservations')
                ->select('id_parking_space')
                ->where('id', $id)
                ->first();
                

                DB::table('reservations')
                ->where('id', $reservation->id)
                ->update([
                    'active' => '0',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                DB::table('parking_spaces')
                ->where('id', $reservation->id_parking_space)
                ->update([
                    'status' => 'Liberado',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }
            
            return redirect()->action([ReservationsController::class, 'index'])->with('success', 'Reservas deletadas');

        }else{
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }

    public function DeleteReservation($id)
    {
        $reservation = Reservations::find($id);
        
        DB::table('parking_spaces')
        ->where('id', $reservation->id_parking_space)
        ->update([
            'status' => 'Liberado',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('reservations')
        ->where('id', $reservation->id)
        ->update([
            'active' => '0',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->action([ReservationsController::class, 'index'])->with('success', 'Reserva deletada');
    }
    */

    public function searchReport(Request $resquest)
    {
        if(session()->has('user')){
            $validatedData = $resquest->validate([
                'type' => 'required|string',
                'initialPeriod' => 'required|string',
                'finalPeriod' => 'required|string',
            ]);

            if($validatedData['type'] == 'Services'){

                $initialPeriodTimeStamp = strtotime($validatedData['initialPeriod']);
                $finalPeriodTimeStamp = strtotime($validatedData['finalPeriod']);
                
                $data = DB::table('services')
                ->selectRaw('services.*, "service" AS `type`, parking_spaces.parking_space_number, users.name AS user')
                ->join('parking_spaces', 'parking_spaces.id', '=', 'services.id_parking_space')
                ->join('users', 'users.id', '=', 'services.id_user')
                ->where('services.created_at', '>=', date('Y-m-d, H:i:s', $initialPeriodTimeStamp))
                ->where('services.created_at', '<=', date('Y-m-d, H:i:s', $finalPeriodTimeStamp))
                ->get();

            }else if($validatedData['type'] == 'Customers'){

                $initialPeriodTimeStamp = strtotime($validatedData['initialPeriod']);
                $finalPeriodTimeStamp = strtotime($validatedData['finalPeriod']);
                
                $data = DB::table('customers')
                ->selectRaw('customers.*, "customer" AS `type`')
                ->where('customers.created_at', '>=', date('Y-m-d, H:i:s', $initialPeriodTimeStamp))
                ->where('customers.created_at', '<=', date('Y-m-d, H:i:s', $finalPeriodTimeStamp))
                ->get();

            }else{

                $initialPeriodTimeStamp = strtotime($validatedData['initialPeriod']);
                $finalPeriodTimeStamp = strtotime($validatedData['finalPeriod']);
                
                $data = DB::table('reservations')
                ->selectRaw('reservations.*, "reservation" AS `type`, parking_spaces.parking_space_number, customers.name AS customer')
                ->join('parking_spaces', 'parking_spaces.id', '=', 'reservations.id_parking_space')
                ->join('customers', 'customers.id', '=', 'reservations.id_customer')
                ->where('reservations.created_at', '>=', date('Y-m-d, H:i:s', $initialPeriodTimeStamp))
                ->where('reservations.created_at', '<=', date('Y-m-d, H:i:s', $finalPeriodTimeStamp))
                ->get();
            }

            return response()->json($data);

        }else{
            return redirect()->action([AdminController::class, 'logout']);
        }
    }

    public function report_pdf(Request $request)
    {
        if (session()->has('user')) {

            $validatedData = $request->validate([
                'type' => 'required|string',
                'initialPeriod' => 'required|string',
                'finalPeriod' => 'required|string',
            ]);

            if($validatedData['type'] == 'Services'){

                $initialPeriodTimeStamp = strtotime($validatedData['initialPeriod']);
                $finalPeriodTimeStamp = strtotime($validatedData['finalPeriod']);
                
                $data = DB::table('services')
                ->selectRaw('services.*, "service" AS `type`, parking_spaces.parking_space_number, users.name AS user')
                ->join('parking_spaces', 'parking_spaces.id', '=', 'services.id_parking_space')
                ->join('users', 'users.id', '=', 'services.id_user')
                ->where('services.created_at', '>=', date('Y-m-d, H:i:s', $initialPeriodTimeStamp))
                ->where('services.created_at', '<=', date('Y-m-d, H:i:s', $finalPeriodTimeStamp))
                ->get();

            }else if($validatedData['type'] == 'Customers'){

                $initialPeriodTimeStamp = strtotime($validatedData['initialPeriod']);
                $finalPeriodTimeStamp = strtotime($validatedData['finalPeriod']);
                
                $data = DB::table('customers')
                ->selectRaw('customers.*, "customer" AS `type`')
                ->where('customers.created_at', '>=', date('Y-m-d, H:i:s', $initialPeriodTimeStamp))
                ->where('customers.created_at', '<=', date('Y-m-d, H:i:s', $finalPeriodTimeStamp))
                ->get();

            }else{

                $initialPeriodTimeStamp = strtotime($validatedData['initialPeriod']);
                $finalPeriodTimeStamp = strtotime($validatedData['finalPeriod']);
                
                $data = DB::table('reservations')
                ->selectRaw('reservations.*, "reservation" AS `type`, parking_spaces.parking_space_number, customers.name AS customer')
                ->join('parking_spaces', 'parking_spaces.id', '=', 'reservations.id_parking_space')
                ->join('customers', 'customers.id', '=', 'reservations.id_customer')
                ->where('reservations.created_at', '>=', date('Y-m-d, H:i:s', $initialPeriodTimeStamp))
                ->where('reservations.created_at', '<=', date('Y-m-d, H:i:s', $finalPeriodTimeStamp))
                ->get();
            }

            $options = new Options();
            $options->setIsRemoteEnabled(true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml(view('layout_pdf.report-'.strtolower($validatedData['type']), ['data'  => $data]));
            $dompdf->render();

            $outputFilename = 'relatorio-' . $validatedData['type'] . '.pdf';

            return response(base64_encode($dompdf->output()))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $outputFilename . '"');


        } else {
            return redirect()->action([AdminController::class, 'logout']);
        }
    }
}
