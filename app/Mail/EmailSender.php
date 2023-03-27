<?php

namespace App\Mail;

use App\Models\Services;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class EmailSender extends Mailable
{
    use Queueable, SerializesModels;

    public $service_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($service_id)
    {
        $this->service_id = $service_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $service = DB::table('services')
                ->select('services.id', 'services.entry_time', 'services.service_code', 'parking_spaces.parking_space_number', 
                'parking_spaces.description AS parking_space_description', 'services.driver_name', 'services.driving_license_number',
                'services.license_plate_number', 'services.vehicle_brand','services.vehicle_model', 'services.vehicle_color', 'services.status', 
                'users.name AS operator_name' )
                ->join('parking_spaces', 'parking_spaces.id', '=', 'services.id_parking_space')
                ->join('users', 'users.id', '=', 'services.id_user')
                ->where('services.id', $this->service_id)
                ->first();

        return $this->view('layout_email.service_receipt')
        ->with(['service' => $service]);
    }
}
