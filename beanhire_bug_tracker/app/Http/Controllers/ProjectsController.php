<?php

namespace App\Http\Controllers;

use \App\Models\project;
use \App\Models\Bug;
use \App\Models\Sprint;
use \App\Models\ProjectStatus;
use \App\Models\DefaultValue;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Validator;

class ProjectsController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit');
        if (empty($limit)) {
            $limit = 10;
        }

        $projectsData = project::paginate($limit);
        // $permissions_list_arr = [];
        $projectsData->getCollection()->transform(function($project) use(&$permissions_list_arr) {
            foreach (config('bug.permissions_list') as $permission) {
                $project->{$permission} = $permission_val = isUserHasActionPermission([
                    'module_id' => config('bug.projects_module_id'),
                    'action' => $permission, // view_access, edit_access, delete_access
                    'modelObj' => $project
                ]);

                // $permissions_list_arr[$permission] = $permission_val;
            }

            return $project;
        });

        $permissions_list_arr = [];
        foreach (config('bug.permissions_list') as $permission) {
            $permissions_list_arr[$permission] = isUserHasActionPermission([
                'module_id' => config('bug.projects_module_id'),
                'action' => $permission, // view_access, edit_access, delete_access
                'get_access_type' => true
            ]);
        }

        $projectsPagenation = $this->objToArray($projectsData);
        $projects = $projectsPagenation['data'];
        $allActiveProjects = project::select('id','name')->where('status','=',1)->get();
        $allActiveProjects = $this->objToArray($allActiveProjects);
        $project_statuses = ProjectStatus::pluck('name','id');
        $project_statuses = $this->objToArray($project_statuses);
        $sprint_statuses = \App\Models\SprintStatus::pluck('name','id');
        $sprint_statuses = $this->objToArray($sprint_statuses);
        // dd($projects);

        return view('projects',compact('projects', 'permissions_list_arr', 'projectsData','allActiveProjects','project_statuses','sprint_statuses'));
    }

    public function project($id){

        $projectsData = project::where('id','=',$id)->get();
        $projectsPagenation = $this->objToArray($projectsData);
        return $projectsPagenation;

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $projectData = $request->all();

        //var_dump($projectData);

        $start_date = Carbon::parse($projectData['start_date']);
        $end_date   = Carbon::parse($projectData['end_date']);
        if (array_key_exists('release_date', $projectData)) {
            $release_date = Carbon::parse($projectData['release_date']);
        } else {
            $release_date = null;
        }

       $valitionResult = Validator::make($request->all(), [
           'start_date' => 'required|date',
           'end_date' => 'required|date|after_or_equal:start_date',
           'release_date'=> 'date|after:start_date|after:end_date',
       ]);


      if($valitionResult->fails()){
          return response()->json(['error'=>$valitionResult->errors()->all()]);
      }


      if(!array_key_exists('release_date', $projectData)){
          $projectData['release_date'] = null;
      }

        project::create([
            'name' => $projectData['name'],
            'description' => $projectData['description'],
            'status'=>$projectData['status'],
            'start_date' =>$start_date,
            'end_date' => $end_date ,
            'release_date' => $release_date,
            'created_by'=>Auth::user()->id
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Project created successfully.'
        ]);

    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    protected function _checkForPermission($options = []) {
        $id = data_get($options, 'id');
        $access_type = data_get($options, 'access_type');

        $projectData = $this->projectData = \App\Models\Project::where('id','=',$id)->first();
        if (empty($projectData)) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid Request.'
            ]);
        }

        $has_permission = isUserHasActionPermission([
            'module_id' => config('bug.projects_module_id'),
            'action' => $access_type, // view_access, edit_access, delete_access
            'modelObj' => $projectData
        ]);

        if (!$has_permission) {
            if (request()->expectsJSON()) {
                return response()->json([
                    'success' => 0,
                    'message' => config('bug.no_permission_to_access_msg')
                ]);
            }

            return redirectWithFlashMsg([
                'url' => url('/projects'),
                'type' => 'danger',
                'message' => config('bug.no_permission_to_access_msg')
            ]);

        }

        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permissionResponse = $this->_checkForPermission([
            'access_type' => 'view_access',
            'id' => $id
        ]);

        if ($permissionResponse !== true) {
            return $permissionResponse;
        }

        $project = $this->projectData;
        $project = $this->objToArray($project);

        return response()->json($project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permissionResponse = $this->_checkForPermission([
            'access_type' => 'edit_access',
            'id' => $id
        ]);

        if ($permissionResponse !== true) {
            return $permissionResponse;
        }

        $projectData = $request->all();

        if(!empty($projectData)){
            $updateProject = project::where('id','=',$id)
                                ->update([
                                'name' => $projectData['name'],
                                'description' => $projectData['description'],
                                'status'=>$projectData['status'],
                                'start_date' => Carbon::parse($projectData['start_date']),
                                'end_date' => Carbon::parse($projectData['end_date']),
                                'release_date' => Carbon::parse($projectData['release_date']),
                            ]);
        }

        if($updateProject){
            return response()->json([
                'success'=>1,
                'message'=>'Project Updated Successfully'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deleteProject($id){

        $project_sprints = Sprint::select('id')->where('project_id','=',$id)->get();
        $project_bugs = Bug::select('id')->where('project_id','=',$id)->get();

        if((sizeof($project_sprints) == 0)&&(sizeof($project_bugs) == 0)){

            $is_deleted = Project::find($id)->delete();

            if($is_deleted){
                return response()->json([
                    'success' => 1,
                    'message' => 'Project deleted succesfully'
                ]);
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'Something went wrong'
                ]);
            }

        } else {
            return response()->json([
                'success' => 0,
                'message' => 'Please delete the sprints and bugs associated with this project'
            ]);
        }

    }

    public function getProjectStatus(){

        $projectStatus = projectStatus::get();
        $projectStatus = $this->objToArray($projectStatus);

        return $projectStatus;

    }

    public function getProjects(){

        $projectsData = project::paginate(10);
        $permissions_list_arr = [];
        $projectsData->getCollection()->transform(function($project) use(&$permissions_list_arr) {
            foreach (config('bug.permissions_list') as $permission) {
                $project->{$permission} = $permission_val = isUserHasActionPermission([
                    'module_id' => config('bug.projects_module_id'),
                    'action' => $permission, // view_access, edit_access, delete_access
                    'modelObj' => $project
                ]);

                $permissions_list_arr[$permission] = $permission_val;
            }

            return $project;
        });

        $projectsPagenation = $this->objToArray($projectsData);
        $projects = $projectsPagenation['data'];

        return $projects;
    }

    public function setDefaultProject($id){

        DefaultValue::create([
            'user_id'=>Auth::user()->id,
            'relation_id'=>$id,
            'relation_type'=>'Project'
        ]);

        return response()->json([
            'success'=>1,
            'message'=>'Default Project set Successfully'
        ]);
   }

    protected function objToArray($getobject) {
        if (!empty($getobject)) {
            $getArray = $getobject->toArray();
        } else {
            $getArray = array();
        }

        return $getArray;
    }
}
