<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\SubModule;
use \App\User;
use Auth;

class SubModulesController extends Controller
{


    public function __construct() {
        $this->middleware('auth');
    }
    
    public function view($id){

        $sub_modules = SubModule::where('module_id',$id)->get();

        $users    = User::selectRaw('id,CONCAT(first_name," ",last_name) as full_name')->pluck('full_name','id');


    	return view('testcases.sub_modules',compact('id','sub_modules','users'));
    }

    public function getModules(){

    	$modules = Module::select('id','name')->pluck('name','id');
    	return $modules;

    }

    public function add(Request $request){
    	$data = $request->all();

    	if(!empty($data)){
    		$is_Module_Created = SubModule::create([
    			'name' => $data['name'],
    			'module_id' => $data['module_id'],
    			'created_by' => Auth::user()->id
    		]);
    	}

    	if($is_Module_Created){
    		return response()->json([
    			'success'=>1,
    			'message'=>'Sub-Module created successfully'
    		]);
    	}
    }
}
