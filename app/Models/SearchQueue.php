<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentData;
class SearchQueue extends Model
{
    use HasFactory;
    protected $fillable=['user_id'];
    public function document_datas(){
        return $this->belongsToMany(DocumentData::class, 'dd_sq_pivot', 'search_queue_id');
    }
}
