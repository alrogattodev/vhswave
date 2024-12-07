<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;
    protected $table = 'medias';
    protected $fillable = [
        'title',
        'genre',
        'availability',
        'rental_price',
        'media_type',
    ];

    const STATUS_AVAILABLE = 1;
    const STATUS_RENTED = 2;

    public static function getStatuses() 
    {
        return [
            self::STATUS_AVAILABLE => 'available',
            self::STATUS_RENTED => 'rented',
        ];
    }

    public function getStatusAttribute(): string
    {
        return self::getStatuses()[$this->availability];
    }
}
