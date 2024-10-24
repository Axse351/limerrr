<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Paket;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with('paket');
    
    if ($request->has('name')) {
        $query->where('nm_konsumen', 'LIKE', '%' . $request->name . '%');
    }

    // Paginate the results (10 records per page)
    $transaksis = $query->paginate(2);

    // Return view with paginated data
    return view('staff.pages.transaksi.index', compact('transaksis'));
    }
    public function index_admin(Request $request)
    {
        $query = Transaksi::with('paket');
    
        if ($request->has('name')) {
            $query->where('nm_konsumen', 'LIKE', '%' . $request->name . '%');
        }
    
        // Paginate the results (10 records per page)
        $transaksis = $query->paginate(2);
    
        // Return view with paginated data
        return view('admin.pages.transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        // Ambil semua data paket dari model Paket
        $pakets = Paket::all();

        // Cek apakah barcode sudah ada di session, hanya generate jika belum ada
        if (!Session::has('barcode')) {
            // Generate barcode jika belum ada di session
            $barcode = 'barcode-' . uniqid(); // Kamu bisa customize format barcode
            Session::put('barcode', $barcode); // Simpan barcode ke session
        } else {
            // Ambil barcode dari session
            $barcode = Session::get('barcode');
        }

        // Kirim data paket dan barcode ke view
        return view('staff.pages.transaksi.create', compact('pakets', 'barcode'));
    }
    public function createadmin()
    {
        // Ambil semua data paket dari model Paket
        $pakets = Paket::all();

        // Cek apakah barcode sudah ada di session, hanya generate jika belum ada
        if (!Session::has('barcode')) {
            // Generate barcode jika belum ada di session
            $barcode = 'barcode-' . uniqid(); // Kamu bisa customize format barcode
            Session::put('barcode', $barcode); // Simpan barcode ke session
        } else {
            // Ambil barcode dari session
            $barcode = Session::get('barcode');
        }

        // Kirim data paket dan barcode ke view
        return view('admin.pages.transaksi.create', compact('pakets', 'barcode'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nm_konsumen' => 'required|string|max:255',
            'nohp' => 'required|string|max:20',
            'paket_id' => 'required|exists:pakets,id',
        ]);
    
        // Ambil barcode dari session
        $barcode = Session::get('barcode', null);
    
        if (!$barcode) {
            return redirect()->back()->with('error', 'Barcode tidak valid.');
        }
    
        // Simpan data ke database
        $transaksi = Transaksi::create([
            'nm_konsumen' => $request->input('nm_konsumen'),
            'nohp' => $request->input('nohp'),
            'paket_id' => $request->input('paket_id'),
            'barcode' => $barcode,
        ]);
    
        Session::forget('barcode');
    
        return redirect()->route('staff.transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function storeadmin(Request $request)
    {
        //validasi input
        $request->validate([
            'nm_konsumen' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'paket_id' => 'required|exists:paket,id',
        ]);

        //ambil barcode dari session
        $barcode = Session::get('barcode', null);

        if (!$barcode) {
            return redirect()->back()->with('error', 'barcode tidak valid');
        }

        $transaksi = Transaksi::create([
            'nm_konsumen' => $request->input('nm_konsumen'),
            'nohp' => $request->input('nohp'),
            'paket_id' => $request->input('paket_id'),
            'barcode' => $barcode,
        ]);
    
        // Hapus barcode dari session setelah digunakan
        Session::forget('barcode');
    
        // Redirect ke halaman admin transaksi
        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi berhasil ditambahkan oleh Admin.');
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
    public function destroy_two($id)
    {
        $transaksi = Transaksi::find($id);
        if ($transaksi) {
            $transaksi->delete();
            return redirect()->route('staff.transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
        }

        return redirect()->route('staff.transaksi.index')->with('error', 'Transaksi tidak ditemukan.');
    }
    
}
