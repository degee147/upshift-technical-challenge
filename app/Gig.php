<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gig extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'description', 'timestamp_start', 'timestamp_end', 'number_of_positions', 'pay_per_hour', 'posted', 'status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
