<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function generate_receipt(Request $request)
    {
        if (session()->has('user')) {

            $validatedData = $request->validate([
                'id_service' => 'required|integer',
            ]);

            $id = $validatedData['id_service'];

            $service = DB::table('services')
                ->select('services.id', 'services.entry_time', 'services.service_code', 'parking_spaces.parking_space_number', 
                'parking_spaces.description AS parking_space_description')
                ->join('parking_spaces', 'parking_spaces.id', '=', 'services.id_parking_space')
                ->where('services.id', $id)
                ->first();

            $options = new Options();
            $options->setIsRemoteEnabled(true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml(view('layout_pdf.service_receipt', ['service'  => $service]));
            $dompdf->render();

            $outputFilename = 'recibo-servico-' . $validatedData['id_service'] . '.pdf';

            return response(base64_encode($dompdf->output()))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $outputFilename . '"');


        } else {
            // Redireciona o usuário para outra view
            return redirect()->action([AdminController::class, 'logout']);
        }
    }
}

?>
