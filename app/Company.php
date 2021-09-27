<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'address',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function gigs()
    {
        return $this->hasMany(Gig::class);
    }

    public function getTotalGigs()
    {
        return $this->hasMany(Gig::class)->where('company_id', $this->id)->count();
    }

    public function getStartedGigs()
    {
        return $this->hasMany(Gig::class)->where('status', "Started")->count();
    }
}
