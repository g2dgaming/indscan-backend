<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entities\Phone;
use App\Models\Entities\DateTime;
use App\Models\Entities\AadharCard;
use App\Models\Entities\PanCard;
use App\Models\Document;
use App\Models\DocumentData;
use App\Models\SearchQueue;
use App\Models\Category;
use App\Jobs\StartSearch;
use Schema;
use Log;
use Validator;
use Auth;

class SearchController extends Controller
{
    public function create(Request $request){
        $queue=SearchQueue::create([
            'user_id'=>Auth::user()->id
        ]);
        $i=time();
        StartSearch::dispatch($request->all(),$queue->id);
        $f=time();
        return response()->json([
            'status'=>true,
            'id'=>$queue->id,
            'time'=>$f-$i,
            'request'=>$request->all()
        ]);
    }
    public function index($id,Request $req){
        $response = array('result' => '');
        $request=['id'=>$id];
        $perpage=10;
        $validator = Validator::make($request, [
            'id'=>'required|exists:search_queues'
        ]);
        
        if ($validator->fails()) {
          $response['errors'] = $validator->messages();
        } else {
            $queue=SearchQueue::find($request['id']);
            $data=$queue->document_datas()->paginate($perpage);
            $response['is_active']=$queue->is_active;
            $response['result']=($data);
        }
        
        return response()->json($response);
    }
}
