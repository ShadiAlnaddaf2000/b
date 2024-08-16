<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'transport_type',
        'imgs'
    ];

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }
}
