<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sprint extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    public $timestamps = true;

    protected $dates = ['deleted_at'];
}
