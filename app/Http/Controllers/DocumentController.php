<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Models\Category;
use App\Models\Document;
use Auth;
class DocumentController extends Controller
{

    public function index(Request $request){
        /*
        {
            ...,
            images:[]
        }
        */
        $input=$request->all();
        $category=$input['doc_category'];
        $category_id=1;
        if(!Category::where('name',$category)->exists()){
            $cat=new Category;
            $cat->name=$category;
            $cat->save();
            $category_id=$cat->id;
        }
        else{
            $category_id=Category::where('name',$category)->first()->id;
        }
        $document=new Document;
        $document->name=$input['doc_name'];
        $document->category_id=$category_id;
        $document->user_id=Auth::user()->id;
        if(isset($input['notes'])){
            $document->notes=$input['notes'];
        }
        $success=$document->save();
        return response()->json([
            'success'=>$success,
            'document_id'=>$success?$document->id:null
        ]);
    }
    public function updateDocument($id,Request $request){
        $document=Document::find($id);
        $document->document_data=$request->all();
        $document->document_data;
        return response()->json($document);
    }
    public function getAll(){
        return response()->json(['result'=>Auth::user()->documents()->paginate(100)]);
    }
    public function getDocument($id){
        return Auth::user()->documents()->where('id',$id)->get();
    }
}
