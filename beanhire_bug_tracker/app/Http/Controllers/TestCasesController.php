<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DefaultValue;
use App\Models\Sprint;
use App\Models\Project;
use App\User;
use App\Models\TestcaseModule;
use App\Models\Testcase;
use App\Models\SubModule;
use App\Models\Counter;
use App\Models\TestcaseStep;
use Auth;

class TestCasesController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }
    
    public function  create($id){

    	$project_id = DefaultValue::select('relation_id AS project_id')
    					   ->where([['relation_type', 'Project'],['user_id', Auth::user()->id]])->first();

    	$project_name = Project::select('name')->where('id',$project_id->project_id)->first();

    	$subModule = SubModule::where('id',$id)->select('id AS subModule_id','name','module_id')->first();

    	$module = TestcaseModule::where('id',$subModule->module_id)->select('id AS module_id','name','iD_Prefix')->first();

    	$id = Counter::where('count_for','LIKE','TestCase')->select('count')->first();

    	$defaultData['project_name'] = $project_name->name;
    	$defaultData['project_id'] = $project_id->project_id;
    	$defaultData['module_name'] = $module->name ;
    	$defaultData['module_id'] = $module->module_id;
    	$defaultData['subModule_name'] = $subModule->name;
    	$defaultData['subModule_id'] = $subModule->subModule_id;
    	$defaultData['testcase_id'] = $module->testcase_prefix."_".($id->count+1);
        $testCaseSteps = [];
        $testCaseStepResults = [];
        $stepsCount = null;
        $operationType = 'add';


    	return view('testcases.add_testcase',compact('id','defaultData','testCaseSteps','testCaseStepResults','stepsCount','operationType'));
    }

    public function store(Request $request){
    	$data = $request->all();


    	$steps = $data['step'];
    	$expectedResults = $data['expectedResult'];
    	$size = sizeof($steps);

    	// var_dump($steps);exit;

    	// echo sizeof($expectedResults);exit;

    	$is_created = Testcase::create([
    		'testcase_id' =>$data['testcase_id'],
            'project_id'=>$data['project_id'],
            'module_id'=>$data['module_id'],
            'sub_module_id'=>$data['subModule_id'],
    		'title'=>$data['title'],
    		'preconditions'=>$data['preconditions'],
    		'created_by'=>Auth::user()->id
    	]);
    	// ['title'=>'HD Topi','description'=>'It solution stuff'],
    	$id = $is_created->id;
    	if($is_created){
    		if(sizeof($steps) == sizeof($expectedResults)){
    			for($i=0; $i<$size; $i++) {
    				$step[] = [
    					'testcase_id'=>$id,
    					'step_description'=>$steps[$i],
    					'expected_result'=>$expectedResults[$i]
    				];
    			}
	
    		}
    		
    		$is_testcase_created = TestcaseStep::insert($step);

    		if($is_testcase_created){
    			return response()->json([
    				'success' =>1,
    				'message' =>'Testcase created Successfully'
    			]);
    		}
    	}

    }

    public function view($id){
        // dd($id);
        $testcases = Testcase::where('sub_module_id',$id)->select('id','testcase_id','title','created_by','created_at','updated_by','updated_at')->get();

        $users    = User::selectRaw('id,CONCAT(first_name," ",last_name) as full_name')->pluck('full_name','id');

        return view('testcases.testcases',compact('id','users'));
    }

    public function getTestcases(Request $request,$id){
        // var_dump($request->all());

        $testcasesData = Testcase::where('sub_module_id',$id)
                         ->select('id','testcase_id','title','created_by','created_at','updated_by','updated_at')
                         ->orderBy('created_at', 'desc')
                         ->paginate(2);

         return response()->json($testcasesData);
    }

   public function edit($id){

        $parentIDs = Testcase::where('id',$id)->select('sub_module_id','module_id')->first();
        $testCase = Testcase::where('id',$id)->first();
        $testCaseSteps = TestcaseStep::where('testcase_id',$id)->pluck('step_description'); 
        $testCaseStepResults = TestcaseStep::where('testcase_id',$id)->pluck('expected_result'); 

        $stepsCount = sizeof($testCaseSteps);

        $operationType = 'edit';

        $project_name = Project::select('name')->where('id',$testCase->project_id)->first();

        $subModule = SubModule::where('id',$testCase->sub_module_id)->select('id AS subModule_id','name','module_id')->first();

        $module = TestcaseModule::where('id',$subModule->module_id)->select('id AS module_id','name','testcase_prefix')->first();

        $defaultData['project_name'] = $project_name->name;
        $defaultData['project_id'] = $testCase->project_id;
        $defaultData['module_name'] = $module->name ;
        $defaultData['module_id'] = $module->module_id;
        $defaultData['subModule_name'] = $subModule->name;
        $defaultData['subModule_id'] = $subModule->subModule_id;
        $defaultData['testcase_id'] = $testCase->testcase_id;
        $defaultData['title'] = $testCase->title;
        $defaultData['preconditions'] = $testCase->preconditions;
        $id = $subModule->subModule_id;
        $testCaseID = $id;

        // dd($defaultData); 

        return view('testcases.add_testcase',compact('id','defaultData','testCaseSteps','testCaseStepResults','stepsCount','operationType','testCaseID'));

   }

   public function update(Request $request,$id){

            $data = $request->all();


            $steps = $data['step'];
            $expectedResults = $data['expectedResult'];
            $size = sizeof($steps);

            $old_steps_ids = TestcaseStep::where('testcase_id',$id)->pluck('id');

            if(!empty($old_steps_ids)){
                $old_steps_ids = $old_steps_ids->toArray();
            } else {
                $old_steps_ids = [];
            }


            $is_updated = Testcase::where('id',$id)
                            ->update([
                                'testcase_id' =>$data['testcase_id'],
                                'project_id'=>$data['project_id'],
                                'module_id'=>$data['module_id'],
                                'sub_module_id'=>$data['subModule_id'],
                                'title'=>$data['title'],
                                'preconditions'=>$data['preconditions'],
                                'updated_by'=>Auth::user()->id
                            ]);
            // ['title'=>'HD Topi','description'=>'It solution stuff'],
            if($is_updated){
                TestcaseStep::whereIn('id',$old_steps_ids)->delete();
                if(sizeof($steps) == sizeof($expectedResults)){
                    for($i=0; $i<$size; $i++) {
                        $step[] = [
                            'testcase_id'=>$id,
                            'step_description'=>$steps[$i],
                            'expected_result'=>$expectedResults[$i]
                        ];
                    }
        
                }
                
                $is_testcase_created = TestcaseStep::insert($step);

                if($is_testcase_created){
                    return response()->json([
                        'success' =>1,
                        'message' =>'Testcase created Successfully'
                    ]);
                }
            }
   }
}
