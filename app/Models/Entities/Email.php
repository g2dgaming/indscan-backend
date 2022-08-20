<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentData;

class Email extends Model
{
    use HasFactory;
    use \App\Traits\isEntity;
    protected $fillable=['email'];
    const keyword_index='email';

}
