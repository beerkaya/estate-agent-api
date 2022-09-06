<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $resource = parent::toArray($request);
        $resource['distance'] = round(($this->distance / 1000), 2) . ' km';
        $resource['time'] = Carbon::parse('0000-00-00')->addMilliseconds($this->time)->format('H:i:s');
        return $resource;
    }
}
