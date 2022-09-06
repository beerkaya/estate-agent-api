<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ContactUpdateRequest extends FormRequest
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
        $contact = $this->route('contact');
        return [
            'name' => ['sometimes', 'string', 'between:2,100'],
            'email' => ['sometimes', 'nullable', 'string', 'email', 'max:100', "unique:contacts,email,{$contact->id},id"],
            'phone' => ['sometimes', 'nullable', 'string', 'min:10', 'max:20', "unique:contacts,phone,{$contact->id},id"],
            'address' => ['sometimes', 'nullable', 'string', 'min:10', 'max:100'],
        ];
    }
}
