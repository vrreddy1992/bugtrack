<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bug extends Model
{
    use SoftDeletes;

  protected $guarded = ['id'];
  public $timestamps = true;

  protected $dates = ['deleted_at'];

  public function setAttribute($key = null, $value = null) {
      $this->attributes[$key] = $value;
  }
}
