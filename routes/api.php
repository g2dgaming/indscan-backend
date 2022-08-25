<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;



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
Route::post('/test/login', [AuthController::class, 'testLogin'])->name('login2');
Route::group(['middleware'=>['auth:sanctum','corsfix'],'prefix'=>'documents' ],function(){
    Route::post('/',[DocumentController::class,'index'])->name('document-create');        
    Route::post('/{id}',[DocumentController::class,'updateDocument'])->name('document-queue');       
    Route::get('/',[DocumentController::class,'getAll']);
    Route::get('/{id}',[DocumentController::class,'getDocument']);
 });
 Route::group(['prefix'=>'category' ],function(){
    Route::get('/',[CategoryController::class,'index'])->name('category');        
 });
 Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('/search/{id}',[SearchController::class,'index']);        
    Route::post('/search',[SearchController::class,'create']);        

 });