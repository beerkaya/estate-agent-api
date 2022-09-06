<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\AppointmentStoreRequest;
use App\Http\Requests\Appointment\AppointmentUpdateRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Repositories\Appointment\AppointmentRepository;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Create a new AppointmentController instance.
     *
     * @return void
     */
    public function __construct(public AppointmentRepository $repository)
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\AppointmentResource
     */
    public function index()
    {
        return AppointmentResource::collection($this->repository->with(['user', 'contact'])->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Appointment\AppointmentStoreRequest  $request
     * @return \App\Http\Resources\AppointmentResource
     */
    public function store(AppointmentStoreRequest $request)
    {
        return AppointmentResource::make($this->repository->create($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \App\Http\Resources\AppointmentResource
     */
    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Appointment\AppointmentUpdateRequest  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \App\Http\Resources\AppointmentResource
     */
    public function update(AppointmentUpdateRequest $request, Appointment $appointment)
    {
        return AppointmentResource::make($this->repository->update($request->validated(), $appointment->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        $this->repository->delete($appointment->id);

    }
}
