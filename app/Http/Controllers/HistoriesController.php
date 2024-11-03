<?php

namespace App\Http\Controllers;

use App\Models\Histories; // Model History
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoriesController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data barcode dari request jika ada
        $barcode = $request->input('barcode');

        // Query untuk mengambil data histori
        $histories = Histories::when($barcode, function ($query, $barcode) {
            return $query->where('barcode', $barcode);
        })->paginate(10);

        return view('staff.pages.histories.index', compact('histories', 'barcode'));
    }
    public function showScanForm()
    {
        // Dapatkan transaksi ID (jika tidak ada, berikan nilai default atau null)
        $transaksiId = null; // Contoh nilai atau didapatkan dari DB

        return view('staff.pages.histories.index', compact('transaksiId'));
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
    public function store(Request $request)
    {
        // Validate the incoming data and handle validation errors
        // $validatedData = $request->validate([
        //     'transaksi_id' => 'required',
        //     'jenis_transaksi' => 'required',
        //     'qty' => 'required',
        //     'tanggal' => 'required',
        //     'jam' => 'required',
        // ]);

        try {
            // Attempt to create a new history record
            $history = Histories::create($request);

            // Check if history was successfully created
            if ($history) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data has been added successfully!',
                    'history' => $history
                ], 201);
            } else {
                // Handle case where history creation failed
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add data. Please try again.'
                ], 500);
            }
        } catch (\Exception $e) {
            dd($e);
            // // Catch any unexpected errors and provide feedback
            // return response()->json([
            //     'success' => false,
            //     'message' => 'An error occurred while processing your request.',
            //     'error' => $e->getMessage() // Only include in debugging or remove in production
            // ], 500);
        }
    }





    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'qrcode' => 'required|string',
            'jenis_transaksi' => 'required|string',
            'qty' => 'required|integer',
            'tanggal' => 'required|date',
            'jam' => 'required|string',
        ]);

        // Find the history entry by ID
        $history = Histories::findOrFail($id);

        // Update the entry with new data
        $history->update([
            'jenis_transaksi' => $request->jenis_transaksi,
            'qty' => $request->qty,
            'tanggal' => now(),
            'jam' => now()->format('H:i:s'),
            // If you need to update the user or namawahana, add those fields here
        ]);

        return redirect()->back()->with('success', 'History updated successfully');
    }

    public function updateQty(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'qty' => 'required|integer',
        ]);

        // Find the history entry by ID
        $history = Histories::findOrFail($id);

        // Update the quantity only
        $history->update([
            'qty' => $request->qty,
            'tanggal' => now(),
            'jam' => now()->format('H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Quantity updated successfully');
    }
}
