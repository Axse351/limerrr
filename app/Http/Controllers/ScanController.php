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
        // Validate the incoming request
        $request->validate([
            'qrcode' => 'required|string', // Validate the QR code input
        ]);
    
        // Find the transaksi associated with the scanned QR code
        $transaksi = Transaksi::where('barcode', $request->qrcode)->first();
    
        if (!$transaksi) {
            return response()->json(['error' => 'Transaksi not found.'], 404);
        }
    
        // Check if there are remaining wahana and porsi
        if ($transaksi->wahana > 0 && $transaksi->porsi > 0) {
            // Decrement wahana and porsi
            $transaksi->decrement('wahana'); // Decrease wahana by 1
            $transaksi->decrement('porsi');   // Decrease porsi by 1
    
            // Return success response
            return response()->json(['success' => 'Scan successful, wahana and porsi decremented.']);
        } else {
            return response()->json(['error' => 'Wahana or porsi already exhausted.'], 400);
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


