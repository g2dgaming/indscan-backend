<?php

namespace App\Helpers;
use App\Models\Category;
use App\Models\DocumentData;
use Storage;
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
    public static function test(){
        $image="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==";
        return [];
    }
}
