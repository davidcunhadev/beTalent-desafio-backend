<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'rating',
        'image_url',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ucfirst($value);
    }
}
