<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable =[
        'facility_id',
        'transport_id',
        'hotel_id',
        'restaurant_id',
        'trip_id',

    ];
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function transport()
    {
        return $this->belongsTo(TransportationCompany::class);
    }
}
