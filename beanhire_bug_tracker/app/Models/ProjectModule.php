<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectModule extends Model
{
  protected $guarded = [];
  public $timestamps = true;


  public function role_modules()
  {
      return $this->hasMany('App\Models\Permission', 'module_id');
  }
}
