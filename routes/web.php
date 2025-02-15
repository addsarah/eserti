<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenyewaanController;
use App\Http\Controllers\ProfileController;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::match(['get', 'post'], '/', function (Request $request) {
    $peserta = Peserta::with('sertifikat')->simplePaginate(50);
    $query = $request->input('search');

    if ($query) {
        $results = DB::table('pesertas')
            ->where('nama_peserta', 'LIKE', '%' . $query . '%')
            ->get();
    } else {
        $results = []; // Provide an empty array if $query is empty
    }

    return view('welcome', compact('peserta', 'results'));
});

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::resource('user','App\Http\Controllers\UserController');
Route::resource('reportuser','App\Http\Controllers\ReportUserController');
Route::resource('peserta','App\Http\Controllers\PesertaController');
Route::resource('reportpeserta','App\Http\Controllers\ReportPesertaController');
Route::resource('sertifikat','App\Http\Controllers\SertifikatController');
Route::resource('reportsertifikat','App\Http\Controllers\ReportSertifikatController');

// Cetak PDF
Route::get('cetak_user','App\Http\Controllers\ReportUserController@cetak_user')->name('cetak_user');
Route::get('cetak_peserta','App\Http\Controllers\ReportPesertaController@cetak_peserta')->name('cetak_peserta');
Route::get('cetak_sertifikat','App\Http\Controllers\ReportSertifikatController@cetak_sertifikat')->name('cetak_sertifikat');
