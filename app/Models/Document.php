<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentData;
use Log;
class Document extends Model
{
    use HasFactory;
    protected $hidden=['user_id','created_at','updated_at'];

    public function setDocumentDataAttribute($data){
        $documentData=new DocumentData;
        $documentData->document_id=$this->id;
        $documentData->image=$data['image'];
        if(isset($data['is_aadhar_card'])){
            $documentData->is_aadhar_card=$data['is_aadhar_card'];
        }
        if(isset($data['is_pan_card'])){
            $documentData->is_pan_card=$data['is_pan_card'];
        }
        $documentData->englishText=$data['englishText'];
        $documentData->hindiText=$data['hindiText'];
        $documentData->entities=$data['entities'];
        $documentData->save();
    }
    public function document_data(){
        return $this->hasMany(DocumentData::class);
    }
}
