<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\WishlistController;

Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

Route::get('/', function () {
    return redirect()->route('products.index');
});
Route::resource('products', ProductController::class)->except(['show']);
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');


Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/products/akun', [AuthController::class, 'profile'])->name('products.akun')->middleware('auth');



// Route::get('/admin/dashboard', function () {
//     return 'Selamat datang Admin!';
// })->middleware('auth')->name('admin.dashboard');




// Route::get('/admin/dashboard', function () {
//     return 'Selamat datang Admin!';
// })->middleware(['auth', 'admin'])->name('admin.dashboard');


// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/admin/dashboard', [AuthController::class, 'index'])->name('admin.dashboard');
// });
