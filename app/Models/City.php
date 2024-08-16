<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'description',
        'country_id',
        'imgs',

    ];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }
    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

}
