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
}
