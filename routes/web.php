<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSheetController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('card/create', [
    WorkSheetController::class, 'create'
])->name('card.create');

Route::post('card', [
    WorkSheetController::class, 'store'
])->name('card.store');

Route::get('card', function () {
    return redirect('card/create');
});
