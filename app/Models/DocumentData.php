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
    protected $hidden=['englishText','hindiText','document_id','updated_at'];
    protected $appends=['category'];
    public function setEntitiesAttribute($array){
        //$this->attributes['entities']=json_encode($value);
        $this->attributes['entities']="";
        
    }
    public function getCreatedAtAttribute($value){
        $date = \Carbon\Carbon::parse($value);
        return $date->format('d/m/Y');
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
        $resize=Image::make($value)->encode('jpg',80);
        $fileName=rand(1000,9000000).time().'.jpg';
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
        return $this->hasMany(Entities\Address::class,'document_data_id','id');
    }
    public function emails(){
        return $this->hasMany(Entities\Email::class);
    }
    public function datetimes(){
        return $this->hasMany(Entities\DateTime::class);
    }
    public function money(){
        return $this->hasMany(Entities\Money::class);
    }
    public function phone_numbers(){
        return $this->hasMany(Entities\Phone::class);
    }
    public function tracking_ids(){
        return $this->hasMany(Entities\Tracking::class);
    }
    public function urls(){
        return $this->hasMany(Entities\Url::class);
    }
}

