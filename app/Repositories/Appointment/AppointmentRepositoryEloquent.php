<?php

namespace App\Repositories\Appointment;

use App\Models\Appointment;
use App\Models\Contact;
use App\Repositories\Appointment\AppointmentRepository;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use Termwind\Components\Dd;

class AppointmentRepositoryEloquent extends BaseRepository implements AppointmentRepository
{
    /**
     * @inheritDoc
     */
    public function model()
    {
        return Appointment::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        //
    }

    public function create(array $attributes)
    {
        $attributes['user_id'] = Auth::user()->id;
        $attributes['contact_id'] = @$attributes['contact_id'] ?? Contact::create($attributes['contact'])->id;
        $attributes['appointment_date'] = Carbon::parse($attributes['appointment_date']);
        $distance_time = $this->calculateDistanceTime($attributes['zip_code']);
        $attributes['leave_date'] = Carbon::parse($attributes['appointment_date'])
            ->addMilliseconds(-1 * $distance_time);
        $attributes['return_date'] = Carbon::parse($attributes['appointment_date'])
            ->addMilliseconds($distance_time)->addHours(1);

        return parent::create($attributes);
    }

    public function update(array $attributes, $id)
    {
        $appointment = $this->find($id);
        $attributes['user_id'] = Auth::user()->id;
        $attributes['contact_id'] = @$attributes['contact_id'] ?? Contact::create($attributes['contact'])->id;

        if ($appointment->appointment_date != $attributes['appointment_date']) {
            $attributes['appointment_date'] = Carbon::parse($attributes['appointment_date']);
            $distance_time = $this->calculateDistanceTime($attributes['zip_code']);
            $attributes['leave_date'] = Carbon::parse($attributes['appointment_date'])
                ->addMilliseconds(-1 * $distance_time);
            $attributes['return_date'] = Carbon::parse($attributes['appointment_date'])
                ->addMilliseconds($distance_time)->addHours(1);
        }

        return parent::update($attributes, $id);
    }

    public function calculateDistanceTime($zip_code)
    {
        $estate_zip_code = env('ZIP_CODE');
        $estate_coordinate = $this->getCoordinateFromZipCode($estate_zip_code);
        $target_coordinate = $this->getCoordinateFromZipCode($zip_code);

        try {
            $key = env('GRAPHHOPPER_KEY');
            $url = 'https://graphhopper.com/api/1/route?vehicle=car&locale=en&key='
                . $key . '&point=' . $estate_coordinate . '&point=' . $target_coordinate;

            $curl = curl_init();
            if ($curl === false) {
                throw new Exception('failed to initialize');
            }

            $headers = [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ];
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);

            curl_close($curl);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $res = json_decode($response);
        return $res->paths[0]->time;
    }

    public function getCoordinateFromZipCode($zip_code)
    {
        try {
            $url = 'https://nominatim.openstreetmap.org/search?q=' . $zip_code . '&format=json';

            $curl = curl_init();
            if ($curl === false) {
                throw new Exception('failed to initialize');
            }

            $headers = [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ];
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);

            curl_close($curl);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
        $res = json_decode($response);

        if ($res === []) {
            throw new Exception('Zip code not found');
        }

        return $res[0]->boundingbox[0] . '%2C' . $res[0]->lon;
    }
}
