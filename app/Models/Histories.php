<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Histories extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaksi_id',
        'jenis_transaksi',
        'tanggal',
        'jam',
        'qty',
    ];
}
