<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;


use App\Http\Controllers\PasswordController;



Route::middleware(['auth', 'checkuserblocked'])->group(function () {
    // Rute yang hanya bisa diakses oleh pengguna yang tidak diblokir
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});


Route::get('auth/blocked', function () {
    return view('auth.blocked');
})->name('auth.blocked');



Route::post('/admin/toggle-block/{id}', [AdminController::class, 'toggleBlock'])->name('admin.toggleBlock');
Route::get('/admin/delete-user', [AdminController::class, 'deleteUserPage'])->name('admin.delete_user');
Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');


Route::get('/admin/logged-in-users', [AdminUserController::class, 'loggedInUsers'])->name('admin.loggedin_users');

Route::middleware(['auth'])->group(function () {
    Route::post('/update-password', [PasswordController::class, 'update'])->name('password.update.manual');
});

Route::get('/admin/block-user', [AdminController::class, 'blockUserPage'])->name('admin.block_user');

// Rute untuk update password manual
Route::post('/update-password', [PasswordController::class, 'update'])->name('password.update.manual');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/users/{id}/toggle-block', [AdminUserController::class, 'toggleBlock'])->name('admin.users.toggleBlock');
    Route::get('/admin/users/{id}/activity', [AdminUserController::class, 'activityLog'])->name('admin.users.activity');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
});


Route::resource('products', ProductController::class)->except(['show']);
Route::get('/products/akun', [AuthController::class, 'profile'])->name('products.akun')->middleware('auth');
Route::get('/products/riwayat', [OrderController::class, 'riwayat'])->name('products.riwayat')->middleware('auth');

Route::get('/categories/{id}/edit', [ProductController::class, 'editCategory'])->name('categories.edit');
Route::put('/categories/{id}', [ProductController::class, 'updateCategory'])->name('categories.update');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
});

Route::post('/api/chatbot', [ChatbotController::class, 'reply']);
Route::post('/chatbot/reply', [ChatbotController::class, 'reply']);
Route::get('/chatbot', function () {
    return view('chatbot.chatbot');
})->name('chatbot.chatbot');

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::get('/get-product-info', function (Request $request) {
    $query = strtolower($request->query('query'));
    $product = Product::where('nama_produk', 'LIKE', "%$query%")->first();
    if (!$product) {
        return response()->json(['reply' => 'Produk tidak ditemukan.']);
    }
    if (str_contains($query, 'stok')) {
        return response()->json(['reply' => "Stok {$product->nama_produk} tersisa {$product->stok}"]);
    }
    if (str_contains($query, 'harga')) {
        return response()->json(['reply' => "Harga {$product->nama_produk} adalah Rp " . number_format($product->harga, 0, ',', '.')]);
    }
    return response()->json(['reply' => 'Maaf, saya tidak mengerti pertanyaan Anda.']);
});

// Route::get('/admin/dashboard', function () {
//     return 'Selamat datang Admin!';
// })->middleware('auth')->name('admin.dashboard');




// Route::get('/admin/dashboard', function () {
//     return 'Selamat datang Admin!';
// })->middleware(['auth', 'admin'])->name('admin.dashboard');


// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/admin/dashboard', [AuthController::class, 'index'])->name('admin.dashboard');
// });
