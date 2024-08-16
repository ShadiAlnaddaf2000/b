<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class
Trip extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'description',
        'imgs',
        'capacity',
        'cost',
        'starting_date',
        'ending_date',
        'user_id',
        'current_number'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function activity()
    {
        return $this->hasOne(Activity::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function favourite()
    {
        return $this->hasMany(Favourite::class);
    }
}
