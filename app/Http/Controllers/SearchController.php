<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entities\Phone;
use App\Models\Entities\DateTime;
use App\Models\Entities\AadharCard;
use App\Models\Entities\PanCard;
use App\Models\Document;
use App\Models\DocumentData;
use App\Models\Category;
use Schema;
use Log;


class SearchController extends Controller
{
    public function index(Request $request){
        $result=[];
        $max_page_count=20;
        $ids=[];
        $query;
        if(isset($request['aadhar_number'])){
            $ids=AadharCard::where('uid','like','%'.$request['aadhar_number'].'%')->get()->pluck('document_data_id');
            $result=DocumentData::whereIn('id',$ids)->get();

        }
        else if(isset($request['pan_number'])){
            $ids=PanCard::where('pan_number','like','%'.$request['pan_number'].'%')->get()->pluck('document_data_id');
            $result=DocumentData::whereIn('id',$ids)->get();
        }
        else{
            $keyword=$request['keyword'];
            if(isset($request['category'])){
                $category=Category::where('name',$request['category'])->first();
                if($category){
                    $query=$category->document_data();
                    /*if(count($ids) > 0  || isset($request['entity'])){
                        $query->whereHas('document',function ($q) use ($ids){
                            $q->whereIn('id',$ids);
                        });
                        
                    }*/
                    $columns=Schema::getColumnListing('document_data');
                    /*$query->where(function($queryContainer)use ($columns,$keyword){
                        foreach($columns  as $key=>$column){
                            if($key == 0){
                                $queryContainer->where('document_data.'.$column,'like','%'.$keyword.'%');
                            }
                            else{
                                $queryContainer->orWhere('document_data.'.$column,'like','%'.$keyword.'%'); 
                            }
                        }      
                    });*/          
                }
            }
            if(!isset($query)){
                $query=DocumentData::query();
            }
            if(isset($request['entity'])){
                $entity=$request['entity'];
                if(isset((config('app.entities_query_builder'))[$entity])){
                    $classname=config('app.entities_query_builder.'.$entity);
                    $ids=$classname::where($classname::keyword_index,'like','%'.$keyword.'%')->get()->pluck('document_data_id');
                    $result=$query->whereIn('document_data.id',$ids)->with('document')->get();
                }
            } 
            else{
                //Raw String Implemenation
                $query->whereHas('document',function($q)use ($keyword){
                    return $q->where('name','like','%'.$keyword.'%')->orWhere('notes','like','%'.$keyword.'%');
                });
                if(isset($request['score'])){
                    $score_entities=$request['score'];
                    $query->orWhere(function($queryContainer)use ($keyword,$score_entities){
                        $count=0;
                        foreach($score_entities as $key=>$value){
                            if($value == 1){
                                $classname=config('app.entities_query_builder.'.$key);
                                if($count == 0){
                                    $queryContainer->whereHas($key,function ($q) use($classname,$keyword){
                                        return $q->where($classname::keyword_index,'like','%'.$keyword.'%');
                                    });
                                }
                                else{
                                    $queryContainer->orWhereHas($key,function ($q) use($classname,$keyword){
                                        return $q->where($classname::keyword_index,'like','%'.$keyword.'%');
                                    });
                                }
                                
                            }
                            $count++;
                        }  
                    });                
                }
                $query->orWhere('englishText','like','%'.$keyword.'%')->orWhere('hindiText','like','%'.$keyword.'%');
                $result=$query->limit($max_page_count)->get();

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
