<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\PairingCode;
use \App\Models\UploadSession;
use \App\Models\User;
use DB;
use Log;
use Validator;
use Auth;
class PairingCodeController extends Controller
{
    public function index(Request $request){
        $user_id=Auth::user()->id;
        $id=rand(100000,999999);
        $session=new PairingCode;
        $session->id=$id;
        $session->user_id=$user_id;
        $save=$session->save();
        return response()->json([
            'success'=>$save,
            'data'=>$save?$session:null
        ]);
    }
    public function link(Request $request){
        $success=false;
        $validator = Validator::make($request->all(), [
            'pairing_code'=>'required|exists:pairing_codes,id',
            'session_id'=>'required|exists:upload_sessions,id'
        ]);
        if(!$validator->fails()){
            if(PairingCode::find($request['pairing_code'])->user_id == UploadSession::find($request['session_id'])->user_id){
                $query=DB::table('links')->where('upload_session_id',$request['session_id'])->where('pairing_code_id',$request['pairing_code']);
                if(!$query->exists()){
                    try{
                        $success=DB::table('links')->insert([
                            'upload_session_id'=>$request['session_id'],
                            'pairing_code_id'=>$request['pairing_code']
                        ]);
                    } catch(\Illuminate\Database\QueryException $e){
                        $success=false;
                    }
                }
            }
            
        }
        return response()->json(['success'=>$success]);
        
    }
    public function isLinked(Request $request){
        return response()->json([
            'is_linked'=> DB::table('links')->where('upload_session_id',$request['session_id'])->where('pairing_code_id',$request['pairing_code'])->exists()
        ]);
    }
}
