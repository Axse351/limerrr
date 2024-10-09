<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Paket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::paginate(20); // Atur jumlah item per halaman
        return view('staff.pages.transaksi.index', compact('transaksis'));
    }
    public function index_admin()
    {
       $transaksis = Transaksi::with('user')->paginate(20); 

       return view('admin.pages.transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        // Ambil semua data paket dari model Paket
        $pakets = Paket::all();

        // Kirim data paket ke view
        return view('staff.pages.transaksi.create', compact('pakets'));
    }
    public function createadmin()
    {
        // Get all paket data for admin
        $pakets = Paket::all();

        // Send paket data to the view
        return view('admin.pages.transaksi.create', compact('pakets'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nm_konsumen' => 'required|string|max:255',
        'nohp' => 'required|string|max:15',
        'paket_id' => 'required|integer',
        'wahana' => 'required|string|max:255',
        'porsi' => 'required|integer',
        'barcode' => 'required|string|max:255',
    ]);

    // Simpan transaksi ke dalam database
    $transaksi = Transaksi::create([
        'nm_konsumen' => $request->nm_konsumen,
        'nohp' => $request->nohp,
        'paket_id' => $request->paket_id,
        'wahana' => $request->wahana,
        'porsi' => $request->porsi,
        'barcode' => $request->barcode,
    ]);

    // Redirect ke halaman index transaksi setelah sukses
    return redirect()->route('staff.transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
}

    
    
    

    public function getPaket($id)
    {
        // Find the Paket by ID
        $paket = Paket::find($id);
    
        // Check if the Paket exists
        if ($paket) {
            return response()->json([
                'wahana' => $paket->wahana,
                'porsi' => $paket->porsi,
                'nm_paket' => $paket->nm_paket // Include the name of the paket if needed
            ]);
        }
    
        return response()->json(['error' => 'Paket not found'], 404);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);
        if ($transaksi) {
            $transaksi->delete();
            return redirect()->route('staff.transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
        }

        return redirect()->route('staff.transaksi.index')->with('error', 'Transaksi tidak ditemukan.');
    }
}
