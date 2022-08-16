<?php
namespace App\Traits;
trait isEntity{
    public function document_data(){
        return $this->belongsTo(\App\Models\DocumentData::class);
    }
}