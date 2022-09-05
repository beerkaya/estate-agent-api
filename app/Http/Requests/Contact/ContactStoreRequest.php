<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'between:2,100'],
            'email' => ['required_if:phone,NULL', 'nullable', 'string', 'email', 'max:100', 'unique:contacts'],
            'phone' => ['required_if:email,NULL', 'nullable', 'string', 'min:10', 'max:20', 'unique:contacts'],
            'address' => ['nullable', 'string', 'min:10', 'max:100'],
        ];
    }
}
