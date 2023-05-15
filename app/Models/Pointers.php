<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pointers extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'longitude',
        'latitude',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
