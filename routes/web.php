<?php

use App\Exports\PurchasingExport;
use App\Exports\SalesExport;
use App\Exports\StockExport;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MenuPermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

//AUTH
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});


Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

//AUTH
Route::get('/home', function () {
    // Jika sudah login, arahkan ke dashboard
    return redirect()->route('dashboard');
});

//dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//product
Route::resource('/product', ProductController::class)->middleware(['auth', 'menu.permission:M2']);

//purchasing
Route::resource('/purchasing', PurchasingController::class)->middleware(['auth', 'menu.permission:M3']);
Route::get('/purchasing/print/{id}', [PurchasingController::class, 'print'])
    ->middleware(['auth', 'menu.permission:M3'])
    ->name('purchasing.print');

//stock
Route::middleware(['auth', 'menu.permission:M4'])->group(function () {
    Route::resource('/stock', StockController::class);
    Route::get('/stock/conversion/{id}', [StockController::class, 'editConversion'])->name('stock.conversion');
    Route::put('/stock/conversion/{id}', [StockController::class, 'updateConversion'])->name('stock.conversion');
});

//sales
Route::middleware(['auth', 'menu.permission:M5'])->group(function () {
    Route::resource('/sales', SalesController::class);
    Route::get('/sales/print/{id}', [SalesController::class, 'print'])->name('sales.print');
});

Route::get('/debt', [SalesController::class, 'debt'])->middleware(['auth', 'menu.permission:M6'])->name('sales.debt');

//export
Route::get('/export-purchasing', function (Request $request) {
    $dateFrom = $request->dateFrom ?? 'ALL';
    $dateTo = $request->dateTo ?? 'ALL';
    $productName = $request->productName ?? 'ALL';

    // Format filename dynamically
    $fileName = "Rekap_Pembelian_{$productName}_{$dateFrom}_to_{$dateTo}.xlsx";
    return Excel::download(new PurchasingExport(
        $request->dateFrom,
        $request->dateTo,
        $request->productName
    ), $fileName);
})->middleware(['auth', 'menu.permission:M3'])->name('export.purchasing');

Route::get('/export-sales', function (Request $request) {
    $dateFrom = $request->dateFrom ?? 'ALL';
    $dateTo = $request->dateTo ?? 'ALL';
    $productName = $request->productName ?? 'ALL';

    $fileName = "Rekap_Penjualan_{$productName}_{$dateFrom}_to_{$dateTo}.xlsx";

    return Excel::download(new SalesExport(
        $request->dateFrom,
        $request->dateTo,
        $request->productName,
        $request->status // Add status parameter
    ), $fileName);
})->middleware(['auth', 'menu.permission:M5'])->name('export.sales');

Route::get('/export-stock', function (Request $request) {
    $productName = $request->productName ?? 'ALL';

    // Format filename dynamically
    $fileName = "Rekap_Stock_{$productName}.xlsx";
    return Excel::download(new StockExport(
        $request->dateFrom,
        $request->dateTo,
        $request->productName
    ), $fileName);
})->middleware(['auth', 'menu.permission:M4'])->name('export.stock');

//user management
Route::resource('/user', UserController::class)->middleware(['auth', 'menu.permission:M7']);

//role management
Route::resource('/role', RoleController::class)->middleware(['auth', 'menu.permission:M9']);

//menu permission management
Route::middleware(['auth', 'menu.permission:M8'])->group(function () {
    Route::get('/menu-permission', [MenuPermissionController::class, 'index'])->name('menu-permission.index');
    Route::put('/menu-permission', [MenuPermissionController::class, 'update'])->name('menu-permission.update');
});
