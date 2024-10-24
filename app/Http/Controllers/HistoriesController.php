<?php

namespace App\Http\Controllers;

use App\Models\Histories; // Model History
use Illuminate\Http\Request;

class HistoriesController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data barcode dari request jika ada
        $barcode = $request->input('barcode');

        // Query untuk mengambil data histori
        $histories = Histories::when($barcode, function ($query, $barcode) {
            return $query->where('barcode', $barcode);
        })->paginate(10); // Pagination data
        
        return view('staff.pages.histories.index', compact('histories', 'barcode'));
    }
    public function index_admin(Request $request)
    {
        // Ambil data barcode dari request jika ada
        $barcode = $request->input('barcode');

        // Query untuk mengambil data histori
        $histories = Histories::when($barcode, function ($query, $barcode) {
            return $query->where('barcode', $barcode);
        })->paginate(10); // Pagination data
        
        return view('admin.pages.histories.index', compact('histories', 'barcode'));
    }
}
