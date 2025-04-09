<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Customers_Report;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\invoiceArchiveController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\Invoices_Report;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
require __DIR__.'/auth.php';
Auth::routes();
Route::get('/', function () {
    return view('Auth.login');
});

Route::get('/invoices',[InvoicesController::class,'index']);
Route::get('/invoices/create',[InvoicesController::class,'create']);
Route::post('/invoices/store',[InvoicesController::class,'store'])->name('invoices.store');
Route::delete('/invoices/{invoice}',[InvoicesController::class,'destroy'])->name('invoices.destroy');
Route::get('/section/{id}',[InvoicesController::class,'getProducts']);
Route::get('/invoices/edit_invoice/{id}',[InvoicesController::class,'edit']);
Route::patch('/invoices/update',[InvoicesController::class,'update']);
Route::get('/invoices/show/{id}',[InvoicesController::class,'show'])->name('Show_status');
Route::post('/Status_Update/{id}',[InvoicesController::class,'Status_Update'])->name('Status_Update');
Route::get('/invoices/invoice_paid',[InvoicesController::class,'invoice_paid']);
Route::get('/invoices/invoice_unpaid',[InvoicesController::class,'invoice_unpaid']);
Route::get('/invoices/invoice_partial',[InvoicesController::class,'invoice_partial']);
Route::get('/invoices/Archive',[invoiceArchiveController::class,'index'] );
Route::patch('/invoices/update',[invoiceArchiveController::class,'update'] )->name('Archive.update');
Route::delete('/destroy',[invoiceArchiveController::class,'destroy'] )->name('Archive.destroy');
Route::get('/Print_invoice/{id}',[InvoicesController::class,'print'] );

Route::get('export_invoices', [InvoicesController::class, 'export']);

//Route::get('/contact', function () {
//    Mail::raw('This is a test email.', function ($message) {
//        $message->to('test@gmail.com')
//            ->subject('Test Email');
//    });
//
//    return "Email sent successfully!";
//});
//////////////////////////////////////////////////////////////////////////////
Route::get('/download/{invoice_number}/{file_name}',[InvoicesDetailsController::class,'gitFile']);
Route::get('/View_file/{invoice_number}/{file_name}',[InvoicesDetailsController::class,'openFile']);
Route::delete('/delete_file',[InvoicesDetailsController::class,'destroy'])->name('delete_file');
Route::get('/InvoicesDetails/{id}',[InvoicesDetailsController::class,'edit']);
//////////////////////////////////////////////////////////////////////////////
Route::post('/InvoiceAttachments',[InvoiceAttachmentsController::class,'store']);
Route::get('MarkAsRead_all',[InvoicesController::class,'MarkAsRead_all'])->name('MarkAsRead_all');
//////////////////////////////////////////////////////////////////////////////
Route::get('/invoices_report',[Invoices_Report::class,'index']);
Route::post('Search_invoices', [Invoices_Report::class,'Search_invoices']);
Route::get('customers_report',  [Customers_Report::class,'index']);
Route::post('Search_customers', [Customers_Report::class,'Search_customers']);

//////////////////////////////////////////////////////////////////////////////
Route::get('/sections',[SectionsController::class,'index']);
Route::post('/sections/store',[SectionsController::class,'store'])->name('sections.store');
Route::patch('/sections/update',[SectionsController::class,'update']);
Route::delete('/sections/destroy',[SectionsController::class,'destroy']);

//////////////////////////////////////////////////////////////////////////////
Route::get('/products',[ProductsController::class,'index']);
Route::post('/products/store',[ProductsController::class,'store'])->name('products.store');
Route::patch('/products/update',[ProductsController::class,'update']);
Route::delete('/products/destroy',[ProductsController::class,'destroy']);

//////////////////////////////////////////////////////////////////////////////
Route::group(['middleware' => ['auth']], function() {

    Route::get('/users',[UserController::class,'index'])->name('users.index');
    Route::get('/users/create',[UserController::class,'create'])->name('users.create');
    Route::get('/users/edit/{id}',[UserController::class,'edit'])->name('users.edit');
    Route::post('/users/store',[UserController::class,'store'])->name('users.store');
    Route::patch('/users/update/{id}',[UserController::class,'update'])->name('users.update');
    Route::delete('/users/destroy',[UserController::class,'destroy'])->name('users.destroy');

    Route::get('/roles',[RoleController::class,'index'])->name('roles.index');
    Route::get('/roles/create',[RoleController::class,'create'])->name('roles.create');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::get('/roles/{id}/show',[RoleController::class,'show'])->name('roles.show');

    Route::post('/roles/store',[RoleController::class,'store'])->name('roles.store');
    Route::patch('/roles/{id}/update',[RoleController::class,'update'])->name('roles.update');
    Route::delete('/roles/{id}/destroy',[RoleController::class,'destroy'])->name('roles.destroy');

});

//////////////////////////////////////////////////////////////////////////////

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/{page}',[AdminController::class,'index']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


