<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentData;
use DB;
class SearchQueue extends Model
{
    use HasFactory;
    protected $fillable=['user_id'];
    public function document_datas(){
        return $this->belongsToMany(DocumentData::class, 'dd_sq_pivot', 'search_queue_id');
    }
    public function getIsActiveAttribute($value){
        if(DB::table('search_queue_activity')->where('search_queue_id',$this->id)->exists()){
            $records=DB::table('search_queue_activity')
            ->where('search_queue_id',$this->id)->get();
            $active=false;
            foreach($records as $record){
                if($record->is_active){
                    $active=true;
                }
            }
            if(!$active){
               DB::table('search_queue_activity')
            ->where('search_queue_id',$this->id)->delete();
            $this->attributes['is_active']=0;
            $this->save();
            }
            return $active;
        }
        else{
            return $value?true:false;
        }
    }
}
