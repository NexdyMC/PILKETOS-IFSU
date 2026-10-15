<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'tb_admin';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'admin',
        'kelas',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}