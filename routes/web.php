<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductResultController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PaketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.pages.login');
});

Route::get('/home', function () {
    return view('auth.pages.login');
});

Route::post('/get-satuan', [ProductController::class, 'getSatuan']);
Route::post('/get-satuan-product', [ProductResultController::class, 'get_satuan_product']);


Route::get('/login', [AuthController::class, 'login'])->name('login.index');
Route::post('/login', [AuthController::class, 'loginStore'])->name('login');

Route::middleware('guest')->group(function () {
    // Landing
    // Route::get('/', [HomeController::class, 'landing']);

    Route::get('/dashboard', [HomeController::class, 'admin']);


    Route::get('/',[AuthController::class, 'login']);


    // Login

    // Register
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'registerStore'])->name('register');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // ============ ADMIN =============

    Route::prefix('admin')->middleware('roleAs:admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.pages.dashboard');
        })->name('admin.dashboard');
    });


    // ============ STAFF =============

    Route::prefix('staff')->middleware('roleAs:staff')->group(function () {
        Route::get('/dashboard', function () {
            return view('staff.pages.dashboard');
        })->name('staff.dashboard');

        // Route::resource('categories', CategoryController::class);
        Route::get('categories', [CategoryController::class, 'index'])->name('staff.categories.index');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('staff.categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('staff.categories.store');
        Route::get('categories/{categories}/edit', [CategoryController::class, 'edit'])->name('staff.categories.edit');
        Route::put('categories/{categories}', [CategoryController::class, 'update'])->name('staff.categories.update');
        Route::delete('categories/{categories}', [CategoryController::class, 'destroy'])->name('staff.categories.destroy');

        Route::get('/scan-transaksi', [TransaksiController::class, 'scanTransaksi'])->name('staff.transaksi.scanTransaksi');
        Route::get('/transaksi/scan', [TransaksiController::class, 'scan'])->name('staff.transaksi.scan');
        Route::get('/scan', [TransaksiController::class, 'scan'])->name('staff.scan.index');
        //Route::resource('transaksi', TransaksiController::class);
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('staff.transaksi.index');
        // Display the form to create a new transaksi (GET)
        Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('staff.transaksi.create');
        Route::get('/paket/create', [PaketController::class, 'create'])->name('staff.paket.create');
        // routes/web.php
        Route::post('/send-whatsapp', [TransaksiController::class, 'sendMessageToWhatsApp'])->name('send.whatsapp');

    // Store a new transaksi (POST)
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('staff.transaksi.store');
    Route::delete('transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('staff.transaksi.destroy');
        // Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('staff.transaksi.create'); // Menampilkan form untuk membuat transaksi baru
        // Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store'); // Menyimpan transaksi baru
        Route::get('/paket', [PaketController::class, 'index'])->name('staff.paket.index');
        Route::get('/get-paket/{id}', [TransaksiController::class, 'getPaket']); // If used specifically in TransaksiController
      
        // Paket routes
        Route::get('/paket', [PaketController::class, 'index'])->name('staff.paket.index');
        Route::get('/get-paket/{id}', [PaketController::class, 'getPaket'])->name('staff.paket.getPaket'); // Use this for PaketController
   
        // Resource route for Paket CRUD operations (index, create, store, edit, update, destroy)
        Route::resource('/paket', PaketController::class, ['as' => 'staff']);

    



        // Route::resource('product', ProductController::class);
        Route::get('product', [ProductController::class, 'index'])->name('staff.product.index');
        Route::get('product/create', [ProductController::class, 'create'])->name('staff.product.create');
        Route::post('product', [ProductController::class, 'store'])->name('staff.product.store');
        Route::get('product/{product}/edit', [ProductController::class, 'edit'])->name('staff.product.edit');
        Route::put('product/{product}', [ProductController::class, 'update'])->name('staff.product.update');
        Route::delete('product/{product}', [ProductController::class, 'destroy'])->name('staff.product.destroy');

        Route::resource('history', HistoryController::class);
        Route::get('history', [HistoryController::class, 'index'])->name('staff.history.index');
        Route::get('history/create', [HistoryController::class, 'create'])->name('staff.history.create');
        Route::post('history', [HistoryController::class, 'store'])->name('staff.history.store');
        Route::get('history/{history}/edit', [HistoryController::class, 'edit'])->name('staff.history.edit');
        Route::put('history/{history}', [HistoryController::class, 'update'])->name('staff.history.update');
        Route::delete('history/{history}', [HistoryController::class, 'destroy'])->name('staff.history.destroy');

        Route::get('storage_one', [ProductResultController::class, 'index_storage_one'])->name('staff.storage_one.index');
        Route::get('storage_one/create', [ProductResultController::class, 'create_storage_one'])->name('staff.storage_one.create');
        Route::post('storage_one', [ProductResultController::class, 'store_storage_one'])->name('staff.storage_one.store');

        Route::get('storage_two', [ProductResultController::class, 'index_storage_two'])->name('staff.storage_two.index');
        Route::get('storage_two/create', [ProductResultController::class, 'create_storage_two'])->name('staff.storage_two.create');
        Route::post('storage_two', [ProductResultController::class, 'store_storage_two'])->name('staff.storage_two.store');

        Route::get('product_result', [ProductResultController::class, 'index_product_result'])->name('staff.product_result.index');
        Route::get('product_result/create', [ProductResultController::class, 'create_product_result'])->name('staff.product_result.create');
        Route::post('product_result', [ProductResultController::class, 'store_product_result'])->name('staff.product_result.store');
    });
});
