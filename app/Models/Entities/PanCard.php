<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanCard extends Model
{
    use HasFactory;
    use \App\Traits\isEntity;
    protected $fillable=['pan_number'];

}
