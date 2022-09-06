<?php

namespace App\Http\Requests\Appointment;

use App\Rules\IsEmptyDate;
use App\Rules\IsValidUKZipCode;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $appointment = $this->route('appointment');
        return [
            'address' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:255', new IsValidUKZipCode],
            'appointment_date' => ['required', 'date', new IsEmptyDate($this->contact_id, $appointment->id)],

            'contact_id' => ['required_if:contact,NULL', 'nullable', 'integer', 'exists:contacts,id'],
            'contact' => ['required_if:contact_id,NULL', 'nullable', 'array'],
            'contact.name' => ['required_with:contact', 'string', 'max:255'],
            'contact.email' => ['required_if:phone,NULL', 'nullable', 'string', 'email', 'max:100', "unique:contacts,email,{$appointment->id},id"],
            'contact.phone' => ['required_if:email,NULL', 'nullable', 'string', 'min:10', 'max:20', "unique:contacts,phone,{$appointment->id},id"],
            'contact.address' => ['nullable', 'string', 'min:10', 'max:100'],
        ];
    }
}
