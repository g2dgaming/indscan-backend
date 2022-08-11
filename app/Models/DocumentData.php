<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

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
        $img=Image::make($value)->encode('png', 75);
        $fileName=time().'.png';
        $path=Storage::put($fileName,'public');
        $url= Storage::url('public/'.$fileName);
        $this->attributes['image']=$url;
    }
    public function getImageAttribute($value){
        return config('app.url').$value;
    }
    public function document(){
        return $this->belongsTo(Document::class);
    }
}

