<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Log;
class DocumentData extends Model
{
    use HasFactory;
    protected $hidden=['englishText','hindiText','document_id','created_at','updated_at'];
    public function setEntitiesAttribute($value){
        $this->attributes['entities']=json_encode($value);
    }
    public function getEntitiesAttribute($value){
        return json_decode($value);
    }
    public function setImageAttribute($value){
        $resize=Image::make($value)->encode('jpg',80);
        $fileName=time().'.jpg';
        $path=Storage::put('public/'.$fileName,$resize->__toString());
        $url= Storage::url('public/'.$fileName);
        $this->attributes['image']=$url;
    }
    public function getImageAttribute($value){
        return asset($value);
    }
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function addresses(){
        return $this->hasMany(Entities/Address::class);
    }
}

