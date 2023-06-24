<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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

            $outputFilename = 'relatorio-' . $validatedData['type'] . '.pdf';

            $options = new Options();
            $options->setIsRemoteEnabled(true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml(view('layout_pdf.report-'.strtolower($validatedData['type']), ['data' => $data, 'outputFilename' => $outputFilename, 'isA4' => true]));
            $dompdf->setPaper('A4', 'landscape');

            $dompdf->render();

            return response(base64_encode($dompdf->output()))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $outputFilename . '"');


        } else {
            return redirect()->action([AdminController::class, 'logout']);
        }
    }

    public function report_excel(Request $request)
    {
        if (session()->has('user')) {

            $validatedData = $request->validate([
                'type' => 'required|string',
                'initialPeriod' => 'required|string',
                'finalPeriod' => 'required|string',
            ]);

            $initialPeriodTimeStamp = strtotime($validatedData['initialPeriod']);
            $finalPeriodTimeStamp = strtotime($validatedData['finalPeriod']);

            $data = [];

            
            if ($validatedData['type'] === 'Services') {

                $data = DB::table('services')
                    ->selectRaw('services.*, "service" AS `type`, parking_spaces.parking_space_number, users.name AS user')
                    ->join('parking_spaces', 'parking_spaces.id', '=', 'services.id_parking_space')
                    ->join('users', 'users.id', '=', 'services.id_user')
                    ->where('services.created_at', '>=', date('Y-m-d, H:i:s', $initialPeriodTimeStamp))
                    ->where('services.created_at', '<=', date('Y-m-d, H:i:s', $finalPeriodTimeStamp))
                    ->get();

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
                $sheet->setCellValue('A1', 'Relatório de serviços');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getFont()->setSize(14);
                $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $sheet->setCellValue('A2', 'ID');
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->setCellValue('B2', 'Tipo de serviço');
                $sheet->getStyle('B2')->getFont()->setBold(true);
                $sheet->setCellValue('C2', 'Valor do serviço');
                $sheet->getStyle('C2')->getFont()->setBold(true);
                $sheet->setCellValue('D2', 'Data/Hora entrada');
                $sheet->getStyle('D2')->getFont()->setBold(true);
                $sheet->setCellValue('E2', 'Data/Hora saída');
                $sheet->getStyle('E2')->getFont()->setBold(true);
                $sheet->setCellValue('F2', 'N° Vaga');
                $sheet->getStyle('F2')->getFont()->setBold(true);
                $sheet->setCellValue('G2', 'Operador');
                $sheet->getStyle('G2')->getFont()->setBold(true);

                $row = 3;
                foreach ($data as $item) {
                    $sheet->setCellValue('A' . $row, $item->id);
                    $sheet->setCellValue('B' . $row, $item->service_type);
                    $sheet->setCellValue('C' . $row, $item->value);
                    $sheet->setCellValue('D' . $row, date('d-m-Y H:i:s', strtotime($item->entry_time)));
                    $sheet->setCellValue('E' . $row, date('d-m-Y H:i:s', strtotime($item->departure_time)));
                    $sheet->setCellValue('F' . $row, $item->parking_space_number);
                    $sheet->setCellValue('G' . $row, $item->user);
                    $row++;
                }

                $sheet->mergeCells('A' . $row . ':G' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $sheet->setCellValue('A' . $row, 'CONTATO');

                $row++;
                $sheet->mergeCells('A' . $row . ':G' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, 'Parking-system - Rua Avenue, N°000, Jardim Teste, Mauá-SP');
                $row++;
                $sheet->mergeCells('A' . $row . ':G' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, '(00)0000-0000');

            } elseif ($validatedData['type'] === 'Customers') {

                $data = DB::table('customers')
                    ->selectRaw('customers.*, "customer" AS `type`')
                    ->where('customers.created_at', '>=', date('Y-m-d, H:i:s', $initialPeriodTimeStamp))
                    ->where('customers.created_at', '<=', date('Y-m-d, H:i:s', $finalPeriodTimeStamp))
                    ->get();

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
                $sheet->setCellValue('A1', 'Relatório de clientes');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getFont()->setSize(14);
                $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $sheet->setCellValue('A2', 'ID');
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->setCellValue('B2', 'Nome');
                $sheet->getStyle('B2')->getFont()->setBold(true);
                $sheet->setCellValue('C2', 'N° Habilitação');
                $sheet->getStyle('C2')->getFont()->setBold(true);
                $sheet->setCellValue('D2', 'E-mail');
                $sheet->getStyle('D2')->getFont()->setBold(true);
                $sheet->setCellValue('E2', 'Telefone');
                $sheet->getStyle('E2')->getFont()->setBold(true);
                $sheet->setCellValue('F2', 'Endereço');
                $sheet->getStyle('F2')->getFont()->setBold(true);
                $sheet->setCellValue('G2', 'Gênero');
                $sheet->getStyle('G2')->getFont()->setBold(true);
                $sheet->setCellValue('H2', 'Idade');
                $sheet->getStyle('H2')->getFont()->setBold(true);
                $sheet->setCellValue('I2', 'Data cadastro');
                $sheet->getStyle('I2')->getFont()->setBold(true);

                $row = 3;
                foreach ($data as $item) {
                    $sheet->setCellValue('A' . $row, $item->id);
                    $sheet->setCellValue('B' . $row, $item->name);
                    $sheet->setCellValue('C' . $row, $item->driving_license_number);
                    $sheet->setCellValue('D' . $row, $item->email);
                    $sheet->setCellValue('E' . $row, $item->phone);
                    $sheet->setCellValue('F' . $row, $item->address);
                    $sheet->setCellValue('G' . $row, $item->gender);
                    $sheet->setCellValue('H' . $row, $item->age);
                    $sheet->setCellValue('I' . $row, date('d-m-Y H:i:s', strtotime($item->created_at)));
                    $row++;
                }

                $sheet->mergeCells('A' . $row . ':I' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $sheet->setCellValue('A' . $row, 'CONTATO');

                $row++;
                $sheet->mergeCells('A' . $row . ':I' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, 'Parking-system - Rua Avenue, N°000, Jardim Teste, Mauá-SP');
                $row++;
                $sheet->mergeCells('A' . $row . ':I' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, '(00)0000-0000');

            } else {

                $data = DB::table('reservations')
                    ->selectRaw('reservations.*, "reservation" AS `type`, parking_spaces.parking_space_number, customers.name AS customer')
                    ->join('parking_spaces', 'parking_spaces.id', '=', 'reservations.id_parking_space')
                    ->join('customers', 'customers.id', '=', 'reservations.id_customer')
                    ->where('reservations.created_at', '>=', date('Y-m-d, H:i:s', $initialPeriodTimeStamp))
                    ->where('reservations.created_at', '<=', date('Y-m-d, H:i:s', $finalPeriodTimeStamp))
                    ->get();

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
                $sheet->setCellValue('A1', 'Relatório de reservas');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getFont()->setSize(14);
                $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $sheet->setCellValue('A2', 'ID');
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->setCellValue('B2', 'Nome cliente');
                $sheet->getStyle('B2')->getFont()->setBold(true);
                $sheet->setCellValue('C2', 'N° vaga');
                $sheet->getStyle('C2')->getFont()->setBold(true);
                $sheet->setCellValue('D2', 'Data/Hora reserva');
                $sheet->getStyle('D2')->getFont()->setBold(true);

                $row = 3;
                foreach ($data as $item) {
                    $sheet->setCellValue('A' . $row, $item->id);
                    $sheet->setCellValue('B' . $row, $item->customer);
                    $sheet->setCellValue('C' . $row, $item->parking_space_number);
                    $sheet->setCellValue('D' . $row, date('d-m-Y H:i:s', strtotime($item->created_at)));
                    $row++;
                }

                $sheet->mergeCells('A' . $row . ':D' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $sheet->setCellValue('A' . $row, 'CONTATO');

                $row++;
                $sheet->mergeCells('A' . $row . ':D' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, 'Parking-system - Rua Avenue, N°000, Jardim Teste, Mauá-SP');
                $row++;
                $sheet->mergeCells('A' . $row . ':D' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, '(00)0000-0000');

            }

            $outputFilename = 'relatorio-' . $validatedData['type'] . '.xlsx';

            $writer = new Xlsx($spreadsheet);
            ob_start();
            $writer->save('php://output');
            $content = ob_get_clean();

            return response(base64_encode($content))
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="' . $outputFilename . '"');
        } else {
            return redirect()->action([AdminController::class, 'logout']);
        }
    }
}
