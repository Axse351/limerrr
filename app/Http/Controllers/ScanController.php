<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Paket;
use App\Models\Histories;
use Illuminate\Support\Facades\Auth;
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
        $validatedData = $request->validate([
            'qrcode' => 'required',
            // Other validations if necessary
        ]);
    
        // Process the scanned QR code
        $scannedData = $validatedData['qrcode'];
    
        // Example: Assume the scanned QR code corresponds to a transaction ID
        // You need to replace this logic with how you retrieve the transaksi_id
        $transaction = Transaksi::where('qrcode', $scannedData)->first(); // Adjust this line according to your logic
    
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found for the scanned QR code.');
        }
    
        $transaksiId = $transaction->id; // Get the transaction ID
    
        // Get the authenticated user or fetch the relevant user
        $user = auth()->user(); // or User::find($userId) if you have a specific user ID
    
        // Prepare data for insertion into the histories table
        $historyData = [
            'transaksi_id' => $transaksiId, // Now it's correctly defined
            'jenis_transaksi' => 'some type', // Set the transaction type
            'tanggal' => now()->toDateString(),
            'jam' => now()->toTimeString(),
            'qty' => 1, // Set quantity appropriately
            'namawahana' => $user->namawahana, // Get namawahana from the user
        ];
    
        // Insert into the histories table
        Histories::create($historyData);
    
        // Redirect or return a response
        return redirect()->back()->with('success', 'Data scanned successfully!');
    }
    
    public function scancoba(Request $request)
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
