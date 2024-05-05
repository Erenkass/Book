<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\OrderController;

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

Route::get('/', [HomeController::class,'home'])->name('welcome');
Route::get('/kitaplar/{id}', [HomeController::class,'show'])->name('users.books.show');

Route::get('/sepet', [ShoppingController::class,'index'])->name('shopping.index');
Route::get('/sepete-ekle/{id}', [ShoppingController::class,'addToCart'])->name('shopping.addToCart');
Route::get('/sepetten-çıkar/{id}', [ShoppingController::class,'removeFromCart'])->name('shopping.removeFromCart');
Route::get('/sepeti-güncelle/{raw_id}/{type}', [ShoppingController::class,'updateCart'])->name('shopping.updateCart');
Route::post('/sepeti-oluştur', [OrderController::class,'store'])->name('orders.store');

Route::post('/siparis-basarili', [OrderController::class,'success'])->name('orders.success');
Route::post('/siparis-basarisiz', [OrderController::class,'fail'])->name('orders.fail');


Route::prefix('admin')->middleware('auth')->group(function (){
   Route::get('/deneme',[TestController::class,'test'])->name('test');
   Route::get('/detail',[TestController::class,'detail'])->name('detail');

   Route::get('/kitaplar',[BookController::class,'index'])->name('books.index');
   Route::get('/kitaplar/ekle',[BookController::class,'create'])->name('books.create');

   Route::post('/kitaplar/ekle',[BookController::class,'store'])->name('books.store');
   Route::get('/kitaplar/{id}',[BookController::class,'edit'])->name('books.edit');
   Route::post('/kitaplar/{id}',[BookController::class,'update'])->name('books.update');
   Route::get('/kitaplar/sil/{id}',[BookController::class,'delete'])->name('books.delete');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
