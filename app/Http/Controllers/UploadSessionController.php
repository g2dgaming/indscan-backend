<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\UploadSession;
use Auth;
use \App\Models\User;
use DB;
use Log;
use Validator;
class UploadSessionController extends Controller
{
    public function index(Request $request){
        $user_id=Auth::user()->id;
        $id=md5(uniqid(rand(), true));
        $session=new UploadSession;
        $session->id=$id;
        $session->user_id=$user_id;
        $save=$session->save();
        return response()->json([
            'success'=>$save,
            'data'=>$save?$session:null
        ]);
    }
    public function unlink(Request $request){
        $success=false;
        $query=DB::table('links')->where('upload_session_id',$request['session_id']);
        if($query->exists()){
            $success=$query->delete();
        }
        return response()->json([
            'success'=>$success>0
        ]);
    }
    public function delete($id){
        $response=[
            'success'=>false
        ];
        $validator = Validator::make(['id'=>$id], [
            'id'=>'required|exists:upload_sessions'
        ]);
        if(!$validator->fails()){
            $done=UploadSession::find($id)->delete();
            DB::table('links')->where('upload_session_id',$id)->delete();
            $response['success']=$done;
        }
        return response()->json($response);
    }
}
