<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    protected $table = 'clients';
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }    
}
