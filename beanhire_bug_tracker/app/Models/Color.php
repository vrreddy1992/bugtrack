<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as MongoEloquent;

class Color extends MongoEloquent
{
    protected $connection = 'mongodb';
}
