<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordReset extends Model
{
    

  protected $guarded = [];
  public $timestamps = false;

  // protected $dates = ['deleted_at'];

  /*public function setAttribute($key = null, $value = null) {
      $this->attributes[$key] = $value;
  }*/
}
