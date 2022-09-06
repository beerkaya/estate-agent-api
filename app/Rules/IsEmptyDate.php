<?php

namespace App\Rules;

use App\Models\Appointment;
use App\Models\Contact;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class IsEmptyDate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(public $contact_id, $appointment_id = null)
    {
        $this->message = 'This appointment date is not empty.';
        $this->contact = Contact::find($contact_id);

        $this->appointment = Appointment::find($appointment_id);
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
        if (!$this->contact && $this->contact_id) {
            $this->message = "The selected contact id is invalid.";
            return false;
        }

        if (!$this->contact) {
            return true;
        }

        $value = Carbon::parse($value);
        $appointments = $this->contact->appointments()
            ->where('leave_date', '<', $value)
            ->where('return_date', '>', $value);
        if ($this->appointment) {
            $appointments = $appointments->where('id', '!=', $this->appointment->id);
        }
        return $appointments->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
