<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;

class ReceiptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        /*
        $service = DB::table('services')
        ->select('services.id', 'services.entry_time', 'parking_spaces.parking_space_number')
        ->join('parking_spaces', 'parking_spaces.id', '=', 'services.id_parking_space')
        ->where('services.id', $id)
        ->first();

        //die(var_dump($id));

        //return  view('layouts.service_receipt', compact($service));
        

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('layouts.service_receipt', ['service'  => $service]));
        $dompdf->render();

        $outputFilename = 'recibo-servico-' . $service->id . '.pdf';

        $dompdf->stream($outputFilename);

        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment;filename="' . $outputFilename . '"');
            */

        $twilio_codes = DB::table('twilio_codes')
        ->select('*')
        ->where('twilio_codes.id', 1)
        ->first();

        $account_sid = env($twilio_codes->twilio_account_sid);
        $auth_token = env($twilio_codes->twilio_auth_token);

        // Inicializa o cliente Twilio com a classe padrão de cliente HTTP
        $client = new Client($account_sid, $auth_token);
        

        // Envia o SMS
        $message = $client->messages->create(
            // Número de telefone do destinatário (com código do país)
            '+5511959089347',
            array(
                // Número de telefone da sua conta Twilio (com código do país)
                'from' => '+5511959089347',
                // Corpo da mensagem SMS
                'body' => 'Hello, World!'.$id
            )
        );

        // Exibe a resposta da API Twilio
        echo $message->sid;
    }

    public function generate_receipt(Request $request)
    {
        if (session()->has('user')) {

            $validatedData = $request->validate([
                'id_service' => 'required|integer',
            ]);

            $id = $validatedData['id_service'];

            $service = DB::table('services')
                ->select('services.id', 'services.entry_time', 'parking_spaces.parking_space_number')
                ->join('parking_spaces', 'parking_spaces.id', '=', 'services.id_parking_space')
                ->where('services.id', $id)
                ->first();

            $dompdf = new Dompdf();
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
