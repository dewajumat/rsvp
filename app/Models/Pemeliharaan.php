<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeliharaan extends Model
{
    use HasFactory;
    protected $table = 'pemeliharaan';
    // protected $fillable = ['total_hrg'];
    // protected $fillable = ['no_pintu', 'nama_brg', 'jmlh_brg', 'hrg_brg' ];
    protected $guarded = [];
    public $timestamps = false;
    // protected $primaryKey = 'no_pintu';
}
