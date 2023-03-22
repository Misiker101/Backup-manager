<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\BackupController;

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

   return view('auth\login');
});

Route::get('/backup', 'BackupController@index');
Route::get('/backup/create', 'BackupController@create');
Route::get('/backup/download/{file_name}', 'BackupController@download');
Route::get('/backup/delete/{file_name}', 'BackupController@delete');

Auth::routes();

Route::get('/home', function () {

     //Storage::disk('google')->put('hello.txt', "Hello World");
     $files = [];
     $deletingFile = [];
     $disks = [];
     return view('vendor/laravel_backup_panel/layout');
})->middleware('auth');
