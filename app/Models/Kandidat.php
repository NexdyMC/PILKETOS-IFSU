<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kandidat extends Model
{
    protected $table = 'tb_kandidat';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['nama', 'kelas', 'visi', 'misi', 'image']; 
}