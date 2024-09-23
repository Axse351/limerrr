<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Paket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use DNS1D; // QRCode 2D
use DNS2D; // QRCode 2D


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
    public function scanTransaksi(Request $request)
{
    $transaksiId = $request->id;

    $transaksi = Transaksi::find($transaksiId);

    if ($transaksi) {
        return response()->json([
            'success' => true,
            'transaksi' => [
                'nm_konsumen' => $transaksi->nm_konsumen,
                'nohp' => $transaksi->nohp,
                'paket_id' => $transaksi->paket_id,
            ],
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Transaksi tidak ditemukan',
    ], 404);
}
    public function scan()
{
    return view('staff.pages.transaksi.scan');
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
        $paket = Paket::find($id);
        return response()->json($paket);
}


    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->delete();
        return redirect()->route('staff.transaksi.index')->with('success', 'Produk deleted successfully');
    }
}