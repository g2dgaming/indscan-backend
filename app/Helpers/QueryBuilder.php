<?php

namespace App\Helpers;
use App\Models\Category;
use App\Models\DocumentData;

class QueryBuilder 
{
    public static function getQuery($request){
        $query=null;
        if(isset($request['category'])){
            $category=Category::where('name',$request['category'])->first();
            $query=$category->document_data();
        }
        if(!$query){
            $query=DocumentData::query();
        }
        return $query;
    }
}
