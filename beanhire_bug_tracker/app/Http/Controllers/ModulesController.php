<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\DefaultValue;
use \App\Models\Sprint;
use \App\Models\Project;
use \App\User;
use \App\Models\TestcaseModule;
use Auth;

class ModulesController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function view()
    {	
    	$default_project = DefaultValue::select('relation_id')
    					   ->where([['relation_type', 'Project'],['user_id', Auth::user()->id]])->first();

    	$sprints = Sprint::select('id','name')->where('project_id',$default_project->relation_id)->pluck('name','id');

        $users    = User::selectRaw('id,CONCAT(first_name," ",last_name) as full_name')->pluck('full_name','id');
    	


    	$modules = TestcaseModule::where('project_id',$default_project->relation_id)->get();
    	//dd($modules);
    	return view('testcases.modules', compact('modules','sprints','users'));
    }

    public function add(Request $request)
    {
    	$data = $request->all();
    	
    	if(!empty($data)){
    		$is_created = TestcaseModule::create([
				    			'name' =>$data['name'],
				    			'project_id'=>$data['project_id'],
				    			'sprint_id'=>$data['sprint_id'],
				    			'author' =>Auth::user()->id,
				    			'testcase_prefix' => $data['iD_Prefix']
				    		]);
    	}

    	if($is_created){
    		return response()->json([
    			'success'=>1,
    			'message'=>'Module created successfully'
    		]);
    	}


    }

    public function getDefaultValues()
    {
    	$default_project = DefaultValue::select('relation_id')
    					   ->where([['relation_type', 'Project'],['user_id', Auth::user()->id]])->first();

    	$project_name = Project::select('name')->where('id',$default_project->relation_id)->first();

    	$sprints = Sprint::select('id','name')->where('project_id',$default_project->relation_id)->pluck('name','id');

    	$arr_response['project_id'] = $default_project->relation_id;
    	$arr_response['project_name'] = $project_name->name;
    	$arr_response['sprints'] = $sprints;

    	return $arr_response; 
    }


    public function viewModule($id){
        
        $data = TestcaseModule::where('id',$id)->first();
        $project_name = Project::where('id',$data->project_id)->select('name')->first();
        $data->project_name =  $project_name->name;
        return response()->json($data);
    }

    public function update(Request $request,$id){
        $data = $request->all();

        if(!empty($data)){
            $is_updated = TestcaseModule::where('id',$id)
                           ->update([
                                'name' =>$data['name'],
                                'project_id'=>$data['project_id'],
                                'sprint_id'=>$data['sprint_id'],
                                'author' =>Auth::user()->id,
                                'iD_Prefix' => $data['iD_Prefix']
                            ]);
        }

        if($is_updated){
            return response()->json([
                'success'=>1,
                'message'=>'Module updated successfully'
            ]);
        }
    }

    public function delete($id){
        $is_deleted = TestcaseModule::find($id)->delete();

        if($is_deleted){
            return response()->json([
                'success'=>1,
                'message'=>'Module deleted successfully'
            ]);
        } else {
            return response()->json([
                'success'=>0,
                'message'=>'Something went wrong'
            ]);
        }
    }

}
