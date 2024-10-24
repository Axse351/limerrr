<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Paket;
use App\Models\Histories;
use Symfony\Component\VarDumper\Caster\RedisCaster;

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
        
        $request->validate([
            'qrcode' => 'required|string', // Validate the QR code
            'action' => 'required|string', // Validate the action
        ]);

        // Assuming your QR code contains data to create a history record
        // You might need to parse the QR code data here.

        $historiesData = [
            'transaksi_id' => $request->input('transaksi_id'), // Replace this with your actual data
            'jenis_transaksi' => $request->input('jenis_transaksi'), // Make sure to send this with the QR code or add logic to determine it
            'tanggal' => now()->format('Y-m-d'), // Current date, you might want to adjust this
            'jam' => now()->format('H:i:s'), // Current time
            'qty' => $request->input('qty'), // Quantity from your QR code or from request
        ];

        // Create the history record
        Histories::create($historiesData);

        return redirect()->route('staff.histories.index')->with('success', 'Data berhasil disimpan');
    }
    

    public function scann(Request $request) {
        $qrcode = $request->input('qrcode');
        
        // Validasi QR Code
        if (!$qrcode) {
            return back()->withErrors('QR Code tidak terbaca!');
        }

        // Proses lebih lanjut (contoh: cek QR code, simpan transaksi, dsb.)
        // Implement your QR code processing logic here

        return redirect()->route('admin.scan.index')->with('success', 'QR Code berhasil diproses: ' . $qrcode);
    }

    public function scannn(Request $request) {
        $qrcode = $request->input('qrcode');
        
        // Validasi QR Code
        if (!$qrcode) {
            return back()->withErrors('QR Code tidak terbaca!');
        }

        // Proses lebih lanjut (contoh: cek QR code, simpan transaksi, dsb.)
        // Implement your QR code processing logic here

        return redirect()->route('scan1.scan.index')->with('success', 'QR Code berhasil diproses: ' . $qrcode);
    }

    public function scannnn(Request $request) {
        $qrcode = $request->input('qrcode');
        
        // Validasi QR Code
        if (!$qrcode) {
            return back()->withErrors('QR Code tidak terbaca!');
        }

        // Proses lebih lanjut (contoh: cek QR code, simpan transaksi, dsb.)
        // Implement your QR code processing logic here

        return redirect()->route('scan1.scan.index')->with('success', 'QR Code berhasil diproses: ' . $qrcode);
    }
    
    public function scannnnn(Request $request) {
        $qrcode = $request->input('qrcode');


        if (!$qrcode) {
            return back()->withErrors('QR Code tidak terbaca');
        }

        return redirect()->route('scan2.scan.index')->with('success', 'QR Code berhasil diproses' . $qrcode);


    }
    
}
