<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        return view('staff.pages.scan.index');
    }

    public function scan(Request $request)
    {
        // Logika untuk memproses hasil scan QR Code
        $qrcodeData = $request->input('qrcode');
        return redirect()->back()->with('success', 'QR Code scanned: ' . $qrcodeData);
    }
}

