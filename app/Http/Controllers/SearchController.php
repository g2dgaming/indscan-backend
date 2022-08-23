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
        StartSearch::dispatch($request->all(),$queue->id);
        return response()->json([
            'status'=>true,
            'id'=>$queue->id
        ]);
    }
    public function index(Request $request){
        $response = array('result' => '', 'success'=>false);
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:search_queues'
        ]);
        
        if ($validator->fails()) {
          $response['errors'] = $validator->messages();
        } else {
            $data=SearchQueue::find($request['id'])->document_datas;
            $response['result']=$data;
        }
        
        return response()->json($response);
    }
}
