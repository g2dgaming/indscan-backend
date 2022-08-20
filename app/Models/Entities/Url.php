<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;
    use \App\Traits\isEntity;
    protected $fillable=['url'];
    const keyword_index='url';

    
}
