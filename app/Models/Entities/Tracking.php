<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;
    use \App\Traits\isEntity;
    protected $fillable=['tracking_id'];
    const keyword_index='tracking_id';

    
}
