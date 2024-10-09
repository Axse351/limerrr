<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'transaksis';

    // Kolom yang bisa diisi
    protected $fillable = [
        'nm_konsumen',
        'nohp',
        'paket_id',
        'wahana',
        'porsi',
        'barcode',
    ];

    /**
     * Relationship to Paket model.
     * 
     * A Transaksi belongs to a Paket.
     */
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by'); // atau 'user_id' tergantung kolomnya
    }
}
