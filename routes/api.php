<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\DocumentController;

/*

|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::group(['middleware'=>'auth:sanctum','prefix'=>'documents' ],function(){
    Route::post('/',[DocumentController::class,'index'])->name('document-create');        
    Route::post('/{id}',[DocumentController::class,'updateDocument'])->name('document-queue');       
    Route::get('/',[DocumentController::class,'getAll']);
    Route::get('/{id}',[DocumentController::class,'getDocument']);
    // authenticated customer routes here 

 });