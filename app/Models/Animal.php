<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'desc',
        'quantity',
        'animal_type_id'
    ];
    public function animalType()
    {
        return $this->belongsTo(AnimalType::class, 'animal_type_id');
    }
}
