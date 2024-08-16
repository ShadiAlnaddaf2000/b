<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $attributes = [
        'rating'=>0
    ];
    protected $fillable=[
        'trip_id',
        'user_id',
        'total_price',
        'person_number'
    ];
    public function trip()
    {
        return $this->belongsTo(Trip::class,'trip_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
