<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DateOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'date',
        'start_time',
        'end_time',
        'is_available'
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
