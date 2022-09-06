<?php

namespace App\Http\Requests\Appointment;

use App\Rules\IsEmptyDate;
use App\Rules\IsValidUKZipCode;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentStoreRequest extends FormRequest
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
        return [
            'address' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:255', new IsValidUKZipCode],
            'appointment_date' => ['required', 'date', new IsEmptyDate($this->contact_id)],

            'contact_id' => ['required_if:contact,NULL', 'nullable', 'integer', 'exists:contacts,id'],
            'contact' => ['required_if:contact_id,NULL', 'nullable', 'array'],
            'contact.name' => ['required_if:contact_id,NULL', 'string', 'max:255'],
            'contact.email' => array_merge(
                ['required_if:phone,NULL,contact_id,NULL', 'nullable', 'string', 'email', 'max:100'],
                !$this->contact_id ? ['unique:contacts,email,NULL,id'] : []
            ),
            'contact.phone' => array_merge(
                ['required_if:email,NULL,contact_id,NULL', 'nullable', 'string', 'min:10', 'max:20'],
                !$this->contact_id ? ['unique:contacts,phone,NULL,id'] : []
            ),
            'contact.address' => ['nullable', 'string', 'min:10', 'max:100'],
        ];
    }
}
