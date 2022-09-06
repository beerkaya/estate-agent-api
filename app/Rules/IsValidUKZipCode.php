<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsValidUKZipCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //remove all whitespace
        $zipcode = preg_replace('/\s/', '', $value);

        //make uppercase
        $zipcode = strtoupper($zipcode);

        if (
            preg_match("/^[A-Z]{1,2}[0-9]{2,3}[A-Z]{2}$/", $zipcode)
            || preg_match("/^[A-Z]{1,2}[0-9]{1}[A-Z]{1}[0-9]{1}[A-Z]{2}$/", $zipcode)
            || preg_match("/^GIR0[A-Z]{2}$/", $zipcode)
        ) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This zip code is not valid.';
    }
}
