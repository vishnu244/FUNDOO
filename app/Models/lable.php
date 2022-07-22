<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lable extends Model
{
    use HasFactory;
    protected $table = 'lables';
    protected $fillable = [
        'lable_id',
        'lable',
              
    ];
}

