<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class ScanController extends Controller
{
    public function index()
    {
        return view('staff.pages.scan.index');
    }
    public function indexscan()
    {
        return view('admin.pages.scan.index');
    }
    public function scan_one()
    {
        return view('scan1.pages.scan.index');
    }
    public function scan_two()
    {
        return view('scan1.pages.scan.index');
    }

    public function scan(Request $request)
    {
        // Dapatkan QR code dari request
        $qrcode = $request->input('qrcode');
        // dd($qrcode);
        // Temukan transaksi berdasarkan QR code
        $transaksi = Transaksi::where('barcode', $qrcode)->first();
    
        if ($transaksi) {
            // Cek apakah wahana masih tersedia
            if ($transaksi->remaining_wahana > 0) {
                // Kurangi jumlah wahana
                $transaksi->remaining_wahana -= 1;
                $transaksi->save(); // Simpan perubahan 
    
                // Berikan feedback ke pengguna
                return redirect()->back()->with('success', 'QR Code scanned and wahana decreased by 1');
            } else {
                return redirect()->back()->with('error', 'No remaining wahana available');
            }
        } else {
            return redirect()->back()->with('error', 'QR Code not found');
        }
    }    
    
    public function scann(Request $request) {
        $qrcode = $request->input('qrcode');
        if (!$qrcode) {
            return back()->withErrors('QR Code tidak terbaca!');
        }
    
        // Proses lebih lanjut (contoh: cek QR code, simpan transaksi, dsb.)
        return redirect()->route('admin.scan.index')->with('success', 'QR Code berhasil diproses' .$qrcode );
    }
    public function scannn(Request $request) {
        $qrcode = $request->input('qrcode');
        if (!$qrcode) {
            return back()->withErrors('QR Code tidak terbaca!');
        }
    
        // Proses lebih lanjut (contoh: cek QR code, simpan transaksi, dsb.)
        return redirect()->route('scan1.scan.index')->with('success', 'QR Code berhasil diproses' .$qrcode );
    }
    public function scannnn(Request $request) {
        $qrcode = $request->input('qrcode');
        if (!$qrcode) {
            return back()->withErrors('QR Code tidak terbaca!');
        }
    
        // Proses lebih lanjut (contoh: cek QR code, simpan transaksi, dsb.)
        return redirect()->route('scan1.scan.index')->with('success', 'QR Code berhasil diproses' .$qrcode );
    }
}


