<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'street',
        'number',
        'complement',
        'city',
        'state',
        'zip_code',
    ];

    protected $hidden = ['client_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function setStreetAttribute($value)
    {
        $this->attributes['street'] = ucwords($value);
    }

    public function setComplementAttribute($value)
    {
        $this->attributes['complement'] = ucfirst($value);
    }

    public function setCityAttribute($value)
    {
        $this->attributes['city'] = ucwords($value);
    }

    public function setStateAttribute($value)
    {
        $this->attributes['state'] = ucwords($value);
    }
}
