<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Paket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::paginate(20); // Atur jumlah item per halaman
        return view('staff.pages.transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        // Ambil semua data paket dari model Paket
        $pakets = Paket::all();

        // Kirim data paket ke view
        return view('staff.pages.transaksi.create', compact('pakets'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nm_konsumen' => 'required|string|max:255',
            'nohp' => 'required|digits_between:10,13',
            'paket_id' => 'required|exists:pakets,id',
            'wahana' => 'required',
            'porsi' => 'required',
        ]);

        try {
            // Simpan transaksi
            $transaksi = Transaksi::create($validatedData);

            // Generate QR code untuk transaksi
            $qrCode = QrCode::format('png')
                ->size(200)
                ->generate('Transaksi ID: ' . $transaksi->id . ', Konsumen: ' . $transaksi->nm_konsumen);

            // Simpan QR Code sebagai gambar di folder public storage
            $fileName = 'qrcodes/' . $transaksi->id . '.png';
            Storage::disk('public')->put($fileName, $qrCode);

            // URL publik dari QR Code
            $qrCodeUrl = asset('storage/' . $fileName);

            return response()->json([
                'success' => true,
                'qrCodeUrl' => $qrCodeUrl,
                'message' => 'Transaksi berhasil disimpan dan QR code telah di-generate.'
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Gagal menyimpan transaksi: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi. Silakan coba lagi.'
            ]);
        }
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
