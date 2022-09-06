<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'zip_code',
        'appointment_date',
        'leave_date',
        'return_date',
        'distance',
        'time',
        'contact_id',
        'user_id'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAppointmentDate($query, $start, $end)
    {
        if (!is_null($start) && !is_null($end)) {
            return $query->where('appointment_date', '>=', Carbon::parse($start))->where('appointment_date', '<=', Carbon::parse($end));
        } elseif (!is_null($start)) {
            return $query->where('appointment_date', '>=', Carbon::parse($start));
        } elseif (!is_null($end)) {
            return $query->where('appointment_date', '<=', Carbon::parse($end));
        }
    }
}
