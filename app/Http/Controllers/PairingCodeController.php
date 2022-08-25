<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\PairingCode;
use \App\Models\User;
use DB;
class PairingCodeController extends Controller
{
    public function index(Request $request){
        $user_id=User::find(1)->id;
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
        $query=DB::table('links')->where('upload_session_id',$request['session_id'])->where('pairing_code_id',$request['pairing_code']);
        if(!$query->exists()){
            $success=DB::table('links')->insert([
                'upload_session_id'=>$request['session_id'],
                'pairing_code_id'=>$request['pairing_code']
            ]);
        }
        return response()->json(['success'=>$success]);
    }
    public function isLinked(Request $request){
        return response()->json([
            'is_linked'=> DB::table('links')->where('upload_session_id',$request['session_id'])->where('pairing_code_id',$request['pairing_code'])->exists()
        ]);
    }
}
