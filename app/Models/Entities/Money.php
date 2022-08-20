<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    use HasFactory;
    use \App\Traits\isEntity;
    protected $fillable=['amount'];
    const keyword_index='amount';

}
