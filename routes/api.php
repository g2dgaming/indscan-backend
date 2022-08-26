<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UploadSessionController;
use App\Http\Controllers\PairingCodeController;




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
Route::group(['middleware'=>['auth:sanctum'],'prefix'=>'documents' ],function(){
    Route::post('/',[DocumentController::class,'index'])->name('document-create');        
    Route::post('/{id}',[DocumentController::class,'updateDocument'])->name('document-queue');       
    Route::get('/',[DocumentController::class,'getAll']);
    Route::get('/{id}',[DocumentController::class,'getDocument']);
 });
 Route::group(['prefix'=>'category' ],function(){
    Route::get('/',[CategoryController::class,'index'])->name('category');        
 });
 Route::group(['middleware'=>['auth:sanctum'],'prefix'=>'sessions' ],function(){
   Route::post('/',[UploadSessionController::class,'index'])->name('session-create');  
   Route::post('/unlink',[UploadSessionController::class,'unlink'])->name('unlink_session');  
   Route::delete('/{id}',[UploadSessionController::class,'delete'])->name('delete_session');  


});
Route::group(['middleware'=>['auth:sanctum'],'prefix'=>'pairing_codes' ],function(){
   Route::post('/',[PairingCodeController::class,'index'])->name('pairing_code_create');  
   Route::post('/link',[PairingCodeController::class,'link'])->name('link_pairing_code');  
   Route::get('/isLinked',[PairingCodeController::class,'isLinked'])->name('check_linked');  
});
 Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('/search/{id}',[SearchController::class,'index']);        
    Route::post('/search',[SearchController::class,'create']);        

 });
 Route::group(['middleware'=>['auth:sanctum'],'prefix'=>'users'],function(){
   Route::get('/2fa',[AuthController::class,'handle2Fa']);        
   Route::post('/2fa',[AuthController::class,'verify2Fa']);        
});