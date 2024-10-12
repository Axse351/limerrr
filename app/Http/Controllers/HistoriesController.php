<?php

namespace App\Http\Controllers;

use App\Models\History; // Model History
use Illuminate\Http\Request;

class HistoriesController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data transaksi dan tampilkan
        $histories = History::paginate(10); // Pagination data
        
        return view('staff.pages.histories.index', compact('histories'));
    }
}
