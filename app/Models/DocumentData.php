<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Models\Entities\Address;
class DocumentData extends Model
{
    use HasFactory;
    protected $hidden=['document_id','updated_at','thumbnail'];
    protected $appends=['category','thumbnail_url'];
    
    public function setEntitiesAttribute($array){
        //$this->attributes['entities']=json_encode($value);
        $this->attributes['entities']="";
        
    }
    public function getCreatedAtAttribute($value){
        $date = \Carbon\Carbon::parse($value);
        return $date->format('d/m/Y');
    }
    public function getThumbnailUrlAttribute(){
        if($this->thumbnail){
            return $this->thumbnail->url;
        }
        else{
            return null;
        }
    }
    public function getCategoryAttribute(){
        return $this->document->category->name;
    }
    public function getEntitiesAttribute($value){
        return [
            'address'=>$this->addresses,
            'datetimes'=>$this->datetimes,
            'money'=>$this->money,
            'phone_numbers'=>$this->phone_numbers,
            'tracking_ids'=>$this->tracking_ids,
            'urls'=>$this->urls
        ];
    }
    public function setImageAttribute($value){
        $resize=Image::make($value)->encode('jpg');
        $fileName=rand(1000,9000000).time().'.jpg';
        $path=Storage::put('public/'.$fileName,$resize->__toString());
        $url= Storage::url('public/'.$fileName);
        $this->attributes['image']=$url;
    }
    public function setThumbnailAttribute($value){
        $img=Image::make($value)->encode('png');
        $fileName=rand(1000,9000000).time().'.png';
        $path=Storage::put('public/'.$fileName,$img->__toString());
        $url= Storage::url('public/'.$fileName);       
        $thumbnail=new Thumbnail;
        $thumbnail->url=$url;
        $thumbnail->save();
        $this->attributes['thumbnail_id']=$thumbnail->id;

    }
    public function getImageAttribute($value){
        return asset($value);
    }
    public function getEntityRelationInstance($classname){
        return $this->$classname();
    }
    public function thumbnail(){
        return $this->belongsTo(Thumbnail::class);
    }
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function address(){
        return $this->hasMany(Entities\Address::class);
    }
    public function email(){
        return $this->hasMany(Entities\Email::class);
    }
    public function date(){
        return $this->hasMany(Entities\DateTime::class);
    }
    public function money(){
        return $this->hasMany(Entities\Money::class);
    }
    public function phone(){
        return $this->hasMany(Entities\Phone::class);
    }
    public function tracking(){
        return $this->hasMany(Entities\Tracking::class);
    }
    public function url(){
        return $this->hasMany(Entities\Url::class);
    }
    public function aadhar_card(){
        return $this->hasOne(Entities\AadharCard::class);
    }
    public function pan_card(){
        return $this->hasOne(Entities\PanCard::Class);
    }
}


