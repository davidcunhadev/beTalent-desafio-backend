<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'phone_number',
    ];

    protected $hidden = ['client_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
