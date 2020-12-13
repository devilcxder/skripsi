<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emotion extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }
}
