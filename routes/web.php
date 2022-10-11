<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\PurchaseDetailController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn () => redirect()->route('login'));

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('home');
//     })->name('dashboard');
// });


Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/categories/data', [CategoryController::class, 'data'])->name('categories.data');
        Route::resource('/categories', CategoryController::class);

        Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
        Route::post('/products/delete_selected', [ProductController::class, 'deleteSelected'])->name('products.delete_selected');
        Route::post('/products/print_barcode', [ProductController::class, 'printBarcode'])->name('products.print_barcode');
        Route::resource('/products', ProductController::class);

        Route::get('/members/data', [MemberController::class, 'data'])->name('members.data');
        Route::post('/members/print_member', [MemberController::class, 'printMember'])->name('members.print_member');
        Route::resource('/members', MemberController::class);

        Route::get('/suppliers/data', [SupplierController::class, 'data'])->name('suppliers.data');
        Route::resource('/suppliers', SupplierController::class);

        Route::get('/expenses/data', [ExpenseController::class, 'data'])->name('expenses.data');
        Route::resource('/expenses', ExpenseController::class);

        Route::get('/purchases/data', [PurchaseController::class, 'data'])->name('purchases.data');
        Route::get('/purchases/{id}/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::resource('/purchases', PurchaseController::class)
            ->except('create');

        Route::get('/purchase-details/{id}/data', [PurchaseDetailController::class, 'data'])->name('purchase-details.data');
        Route::get('/purchase-details/loadform/{discount}/{total}', [PurchaseDetailController::class, 'loadForm'])->name('purchase-details.load_form');
        Route::resource('/purchase-details', PurchaseDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/sales/data', [SaleController::class, 'data'])->name('sales.data');
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');
        Route::delete('/sales/{id}', [SaleController::class, 'destroy'])->name('sales.destroy');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/transactions/new', [SaleController::class, 'create'])->name('transactions.new');
        Route::post('/transactions/save', [SaleController::class, 'store'])->name('transactions.save');
        Route::get('/transactions/finish', [SaleController::class, 'finish'])->name('transactions.finish');
        Route::get('/transactions/nota_small', [SaleController::class, 'notaSmall'])->name('transactions.nota_small');
        Route::get('/transactions/nota_big', [SaleController::class, 'notaBig'])->name('transactions.nota_big');

        Route::get('/transactions/{id}/data', [SaleDetailController::class, 'data'])->name('transactions.data');
        Route::get('/transactions/loadform/{discount}/{total}/{received}', [SaleDetailController::class, 'loadForm'])->name('transactions.load_form');
        Route::resource('/transactions', SaleDetailController::class)
            ->except('create', 'show', 'edit');
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
        Route::get('/report/data/{start}/{end}', [ReportController::class, 'data'])->name('report.data');
        Route::get('/report/pdf/{start}/{end}', [ReportController::class, 'exportPDF'])->name('report.export_pdf');

        Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
        Route::resource('/users', UserController::class);

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/first', [SettingController::class, 'show'])->name('settings.show');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('users.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('users.update_profil');
    });
});