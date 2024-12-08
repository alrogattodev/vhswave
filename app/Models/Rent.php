<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    protected $fillable = [
        'client_id', 
        'media_id', 
        'rented_at', 
        'due_date', 
        'returned_at', 
        'returned'
    ];

    public function client()
    {
        return $this->belongTo(Client::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
