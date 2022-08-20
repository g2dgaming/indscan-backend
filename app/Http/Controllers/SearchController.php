<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entities\Phone;
use App\Models\Entities\DateTime;
use App\Models\Document;
use App\Models\DocumentData;
use App\Models\Category;
use Schema;
use Log;


class SearchController extends Controller
{
    public function index(Request $request){
        $result=[];
        $keyword=$request['keyword'];
        $ids=[];
        if(isset($request['entity'])){
            $entity=$request['entity'];
            if(isset((config('app.entities_query_builder'))[$entity])){
                $classname=config('app.entities_query_builder.'.$entity);
                $ids=$classname::where($classname::keyword_index,'like','%'.$keyword.'%')->get()->pluck('document_data_id');
                $result=DocumentData::whereIn('id',$ids)->with('document')->get();
            }
            else{
                $ids=[];
            }
        }
        else if(isset($request['category'])){
            $category=Category::where('name',$request['category'])->first();
            if($category){
                $query=$category->document_data();
                if(count($ids) > 0  || isset($request['entity'])){
                    $query->whereHas('document',function ($q) use ($ids){
                        $q->whereIn('id',$ids);
                    });
                    
                }
                $columns=Schema::getColumnListing('document_data');
                $query->where(function($queryContainer)use ($columns,$keyword){
                    foreach($columns  as $key=>$column){
                        if($key == 0){
                            $queryContainer->where('document_data.'.$column,'like','%'.$keyword.'%');
                        }
                        else{
                            $queryContainer->orWhere('document_data.'.$column,'like','%'.$keyword.'%'); 
                        }
                    }      
                });          
                $result=$query->get();
            }
        }
        $response=[];
        if(!count($result)){
            $response['result']=[];
        }
        else{
            $response['result']=$result;
        }
        return response()->json($response);

    }
}
