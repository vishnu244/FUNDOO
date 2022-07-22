<?php

namespace App\Models;
use Illuminate\Foundation\Auth\create as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class create extends Model
{
    use HasFactory;
    protected $table = 'creates';
    protected $fillable = [
        'UserName',
        'Email',
        'Password',
        'MobileNumber',
        'Address',
    ];
}
