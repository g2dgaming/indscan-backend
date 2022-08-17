<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entities\Phone;
use App\Models\Entities\DateTime;
use App\Models\Document;
use App\Models\DocumentData;


class SearchController extends Controller
{
    public function index(Request $request){
        $result;
        $keyword=$request['keyword'];
        if(isset($request['entity'])){
            $entity=$request['entity'];
            if($entity == 'phone_number'){
               $result=Phone::where('phone_number','like','%'.$keyword.'%')->get()->pluck('document_data_id');
            }
            else if($entity == 'datetime'){
                $result=DateTime::where('datetime','like','%'.$keyword.'%')->get()->pluck('document_data_id');
            }
        }
        $document_datas=DocumentData::whereIn('id',$result)->with('document')->get();
        return response()->json([
            'result'=>$document_datas
        ]);
    }
}
