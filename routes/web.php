<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductResultController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\ScanController;
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
    Route::get('/dashboard', [HomeController::class, 'admin']);
    Route::get('/',[AuthController::class, 'login']);
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

        // Categories routes
        Route::get('categories', [CategoryController::class, 'index'])->name('staff.categories.index');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('staff.categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('staff.categories.store');
        Route::get('categories/{categories}/edit', [CategoryController::class, 'edit'])->name('staff.categories.edit');
        Route::put('categories/{categories}', [CategoryController::class, 'update'])->name('staff.categories.update');
        Route::delete('categories/{categories}', [CategoryController::class, 'destroy'])->name('staff.categories.destroy');

        // Scan routes
        Route::get('/scan', [ScanController::class, 'index'])->name('staff.scan.index');
        Route::post('/scan', [ScanController::class, 'scan'])->name('scan.process');

        // Transaksi routes
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('staff.transaksi.index');
        Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('staff.transaksi.create');
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('staff.transaksi.store');
        Route::delete('transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('staff.transaksi.destroy');

        // Paket routes
        Route::get('/paket', [PaketController::class, 'index'])->name('staff.paket.index');
        Route::get('/paket/create', [PaketController::class, 'create'])->name('staff.paket.create');
        Route::post('/paket', [PaketController::class, 'store'])->name('staff.paket.store');
        Route::get('/get-paket/{id}', [TransaksiController::class, 'getPaket'])->name('staff.paket.getPaket'); // Named route

        // Product routes
        Route::get('product', [ProductController::class, 'index'])->name('staff.product.index');
        Route::get('product/create', [ProductController::class, 'create'])->name('staff.product.create');
        Route::post('product', [ProductController::class, 'store'])->name('staff.product.store');
        Route::get('product/{product}/edit', [ProductController::class, 'edit'])->name('staff.product.edit');
        Route::put('product/{product}', [ProductController::class, 'update'])->name('staff.product.update');
        Route::delete('product/{product}', [ProductController::class, 'destroy'])->name('staff.product.destroy');

        // History routes
        Route::resource('history', HistoryController::class);
    });
});
