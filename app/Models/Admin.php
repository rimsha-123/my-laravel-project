<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Admin extends Model implements Authenticatable
{
    use AuthenticatableTrait, HasFactory;

    protected $table = 'admins';
    // Specify the primary key column (default is 'id')
    protected $primaryKey = 'id';

    // Allow mass assignment on these fields
    protected $fillable = [
        'firstname',
        'lastname',
        
        'email',
        'password',
        'confirm_password'
    ];

    // Hide the password field when the model is converted to an array or JSON
    protected $hidden = [
        'password',
    ];
}
