<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadSession extends Model
{
    use HasFactory;
    protected $fillable=['user_id'];
    protected $hidden=['user_id','created_at','updated_at'];
    public $incrementing = false;
    protected $keyType = 'string';

}
