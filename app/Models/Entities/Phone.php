<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;
    use \App\Traits\isEntity;
    protected $fillable=['phone_number'];
    const keyword_index='phone_number';

}
