<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentData;
use Log;
class Document extends Model
{
    use HasFactory;
    protected $hidden=['user_id','created_at','updated_at','category'];
    public function category(){
        return $this->belongsTo(Category::class);
    }
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
        $documentData->save();
        $addresses=$data['entities']['address'];
        if($documentData->save()){
            foreach($addresses as $addr){
                $documentData->addresses()->create(
                    [
                    'textAddress'=>$addr,
                    ]
                );
            }
            $dates=$data['entities']['date'];
            foreach($dates as $date){
                $documentData->datetimes()->create([
                    'datetime'=>$date
                ]);
            }
            $emails=$data['entities']['email'];
            foreach($emails as $email){
                $documentData->emails()->create([
                    'email'=>$email
                ]);
            }
            $money=$data['entities']['money'];
            foreach($money as $m){
                $documentData->money()->create([
                    'amount'=>$m
                ]);
            }
            $phone_numbers=$data['entities']['phone'];
            foreach($phone_numbers as $number){
                $documentData->phone_numbers()->create([
                    'phone_number'=>$number
                ]);
            }
            $tracking_ids=$data['entities']['tracking'];
            foreach($tracking_ids as $tracking_id){
                $documentData->tracking_ids()->create([
                    'tracking_id'=>$tracking_id
                ]);
            }
            $urls=$data['entities']['url'];
            foreach($urls as $url){
                $documentData->urls()->create([
                    'url'=>$url
                ]);
            }
        }
    }
    public function document_data(){
        return $this->hasMany(DocumentData::class);
    }
}
