<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;

class BugActivityController extends Controller
{
    public function view(){
    	$users = Color::all();
    	dd($users->toArray());
    }
}
