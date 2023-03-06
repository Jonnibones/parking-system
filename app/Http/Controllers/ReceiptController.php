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

        $account_sid = env('TWILIO_ACCOUNT_SID');
        $auth_token = env('TWILIO_AUTH_TOKEN');

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
}

?>
