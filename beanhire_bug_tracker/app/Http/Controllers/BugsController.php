<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use \App\User;
use \App\Models\Project;
use \App\Models\BugComment;
use \App\Models\BugStatus;
use \App\Models\Severity;
use Auth;
use \App\Models\DefaultValue;
use \App\Models\Sprint;
use \App\Mail\BugNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Models\Activity;


class BugsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lastBugId = \App\Models\Bug::withTrashed()->select('bug_code')->orderBy('bug_code', 'DESC')->first();
        $lastBugId = $this->objToArray($lastBugId);

        if ($lastBugId == null) {
            $currentBugId = 1;
        } else {
            $currentBugId = $lastBugId['bug_code']+1;
        }

        $projects = $this->projects();
        $modules  = $this->modules();
        $sprints  = [];
        $status   = $this->status();
        $severity = $this->severity();
        $users    = $this->users();
        $bugData  = '';
        $operation = "'add'";
        $default_project = getDefaultProject();
        if (empty($default_project)) {
            $default_project =0;
            $default_sprints = [];
        } else {
            $default_sprints = Sprint::where('project_id','=',$default_project)->select('id','name')->pluck('name','id');
        }
        $default_reportedby = Auth::user()->id;
        $files = [];
        $allActiveProjects = project::select('id','name')->where('status','=',1)->get();
        // dd($allActiveProjects);

        $permissions_list_arr = [];
        foreach (config('bug.permissions_list') as $permission) {
            $permissions_list_arr[$permission] = isUserHasActionPermission([
                'module_id' => config('bug.bug_module_id'),
                'action' => $permission, // view_access, edit_access, delete_access
                'get_access_type' => true
            ]);
        }

        $comments = [];


        return view('addBug' , compact('currentBugId','projects','modules','sprints','status','severity','users','bugData','operation','default_project','default_reportedby','files','allActiveProjects','default_sprints','permissions_list_arr','comments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();



        $files = $request->file('bug_docs');

        $bugData = json_decode($data['bugFormData'],true);

        $is_bugcode_exists =  \App\Models\Bug::select('id')->where('bug_code',$bugData['bug_code'])->first();

     
        if($is_bugcode_exists != null) {
            $bugData['bug_code'] = $bugData['bug_code'] + 1;
        }

        if(!empty($bugData)){
            $result = \App\Models\Bug::create([
                'bug_code' => $bugData['bug_code'],
                'project_id' => $bugData['project_id'],
                'sprint_id' => $bugData['sprint_id'],
                'status_id' => $bugData['status_id'],
                'severity_id' => $bugData['severity_id'],
                'reported_by' => $bugData['reported_by'],
                'assigned_to' => $bugData['assigned_to'],
                'description' => $bugData['description'],
                'title' => $bugData['title'],
                'created_by' => Auth::user()->id
            ]);
        }

        if(!empty($files)){
            foreach ($files as $key => $value) {
                $path = $value->store('BugDocuments');
                $name = $value->getClientOriginalName();
                $docs_paths[$key]['original_name'] = $name;
                $docs_paths[$key]['path'] = $path;
                $docs_paths[$key]['unique_name'] = basename($path);
            }

            //dd($docs_paths);
            foreach ($docs_paths as $key => $fileDetails) {
                $insert[] = [
                    'bug_id' =>$result->id,
                    'unique_name'=>$fileDetails['unique_name'],
                    'name'=>$fileDetails['original_name'],
                    'path'=>$fileDetails['path']
                ];
            }

            //dd($insert);
            \App\Models\BugDoc::insert($insert);
        }


        $bugMailData['bug_code'] = $bugData['bug_code'];
        $bugMailData['title'] =  $bugData['title'];
        $projectName = Project::select('name')->find($bugData['project_id']);
        $projectName = $this->objToArray($projectName);
        $bugMailData['project'] = $projectName['name'];
        $sprintName = Sprint::select('name')->find($bugData['sprint_id']);
        $sprintName = $this->objToArray($sprintName);
        $bugMailData['sprint'] = $sprintName['name'];
        $email_assigned = \App\User::select('email','first_name','last_name')->find($bugData['assigned_to']);
        $email_assigned = $this->objToArray($email_assigned);
        $to_email = $email_assigned['email'];
        $bugMailData['assigned_to'] =  $email_assigned['first_name']." ".$email_assigned['last_name'];
        $bug_status = BugStatus::select('name')->find($bugData['status_id']);
        $bug_status = $this->objToArray($bug_status);
        $bug_severity = Severity::select('name')->find($bugData['severity_id']);
        $bug_severity = $this->objToArray($bug_severity);
        $bugMailData['severity'] = $bug_severity['name'];
        $bugMailData['status'] =  $bug_status['name'];
        $bugMailData['assigned_by'] = Auth::user()->first_name." ".Auth::user()->last_name;
        $bugMailData['bug_id'] =  $result->id;
        $bugMailData['subject'] = "BG-".$bugData['bug_code'].",".$bugData['title'];
        $bugMailData['old_status'] = null;
        $bugMailData['new_status'] = null;


        Mail::to($to_email)->send(new BugNotification($bugMailData));

        return $result->id;



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

        $bugData = $this->bugData;
        $bugData = $this->objToArray($bugData);


        $projects = $this->projects();
        $modules  = $this->modules();
        $sprints  = $this->sprints();
        $status   = $this->status();
        $severity = $this->severity();
        $users    = $this->users();
        $currentBugId = 5;
        $operation = "'view'";
        $default_project = getDefaultProject();
        if(empty($default_project )){
            $default_project = 0;
        }
        $default_reportedby = Auth::user()->id;

        $files = \App\Models\BugDoc::select('id','name')->where('bug_id','=',$id)->get();
        $files = $this->objToArray($files);
        if(empty($files)){
            $files = [];
        }
        $allActiveProjects = project::select('id','name')->where('status','=',1)->get();
        $default_sprints   = [];

        $permissions_list_arr = [];
        foreach (config('bug.permissions_list') as $permission) {
            $permissions_list_arr[$permission] = isUserHasActionPermission([
                'module_id' => config('bug.bug_module_id'),
                'action' => $permission, // view_access, edit_access, delete_access
                'get_access_type' => true
            ]);
        }

        $comments = BugComment::where('bug_id',$id)->get();
        

        return view('viewBug',compact('bugData','permissions_list_arr','currentBugId','projects','modules','sprints','status','severity','users','operation','default_project','default_reportedby','files','allActiveProjects','default_sprints','comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permissionResponse = $this->_checkForPermission([
            'access_type' => 'edit_access',
            'id' => $id
        ]);

        if ($permissionResponse !== true) {
            return $permissionResponse;
        }

        $bugData = $this->objToArray($this->bugData);

        $projects = $this->projects();
        $modules  = $this->modules();
        $sprints  = [];
        $status   = $this->status();
        $severity = $this->severity();
        $users    = $this->users();
        $currentBugId = 5;
        $operation = "'edit'";
        $default_project = getDefaultProject();
        if(empty($default_project )){
            $default_project = 0;
        }
        $default_reportedby = Auth::user()->id;
        $files = \App\Models\BugDoc::select('id','name')->where('bug_id','=',$id)->get();
        $files = $this->objToArray($files);
        if(empty($files)){
            $files = [];
        }
        $allActiveProjects = project::select('id','name')->where('status','=',1)->get();
        $default_sprints = [];
        $permissions_list_arr = [];
        foreach (config('bug.permissions_list') as $permission) {
            $permissions_list_arr[$permission] = isUserHasActionPermission([
                'module_id' => config('bug.bug_module_id'),
                'action' => $permission, // view_access, edit_access, delete_access
                'get_access_type' => true
            ]);
        }

        $comments = BugComment::where('bug_id',$id)->get();

        return view('addBug',compact('bugData','currentBugId','projects','modules','sprints','status','severity','users','operation','default_project','default_reportedby','files','allActiveProjects','default_sprints','permissions_list_arr','comments'));
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
         //echo 'Test';

        $permissionResponse = $this->_checkForPermission([
            'access_type' => 'edit_access',
            'id' => $id
        ]);

        if ($permissionResponse !== true) {
            return $permissionResponse;
        }

        $data = $request->all();

        
        $files = $request->file('bug_docs');
        $bugData = json_decode($data['bugFormData'],true);
        $projects = $this->projects();
        $sprints  = $this->sprints();
        $users    = $this->users();
        $statuses = $this->status();
        $severities = $this->severity();

        // dd($bugData);
        $activity_columns = ['assigned_to','severity_id','sprint_id','status_id','project_id'];
        $old_data = \App\Models\Bug::where('id',$id)->select($activity_columns)->first();
        $activity_arr = [];
        foreach ($activity_columns as $column_id => $column_name) {
            if($column_name == 'assigned_to'){
                if($bugData['assigned_to'] !=  $old_data['assigned_to']){
                        $activity['bug_id'] = $id;
                        $activity['activity'] = 'Bug assigned form '.$users [$old_data['assigned_to']].' to '
                                                .$users[$bugData['assigned_to']];
                        $activity['time'] =  date("d-M-y");
                        $activity['activity_by'] = $users[Auth::user()->id];
                        $activity_arr[] = $activity;

                    }
                }
            if($column_name == 'severity_id'){
                if($bugData['severity_id'] !=  $old_data['severity_id']){
                        $activity['bug_id'] = $id;
                        $activity['activity'] = 'Bug severity changed from '.$severities[$old_data['severity_id']].' to '
                                                .$severities[$bugData['severity_id']];
                        $activity['time'] =  date("d-M-y");
                        $activity['activity_by'] = $users[Auth::user()->id];
                        $activity_arr[] = $activity;

                    }
                }
            if($column_name == 'sprint_id'){
                if($bugData['sprint_id'] !=  $old_data['sprint_id']){
                        $activity['bug_id'] = $id;
                        $activity['activity'] = 'Bug Sprint changed from '. $sprints[$old_data['sprint_id']].' to '
                                                .$sprints[$bugData['sprint_id']];
                        $activity['time'] =  date("d-M-y");
                        $activity['activity_by'] = $users[Auth::user()->id];
                        $activity_arr[] = $activity;

                    }
                }
            if($column_name == 'status_id'){
                if($bugData['status_id'] !=  $old_data['status_id']){
                        $activity['bug_id'] = $id;
                        $activity['activity'] = 'Bug status changed from '. $statuses[$old_data['status_id']].' to '
                                                .$statuses[$bugData['status_id']];
                        $activity['time'] =  date("d-M-y");
                        $activity['activity_by'] = $users[Auth::user()->id];                        
                        $activity_arr[] = $activity;

                    }
                }

            if($column_name == 'project_id'){
                if($bugData['project_id'] !=  $old_data['project_id']){
                        $activity['bug_id'] = $id;
                        $activity['activity'] = 'Project changed from '.$projects[$old_data['project_id']].' to '
                                                .$projects[$bugData['project_id']];
                        $activity['time'] =  date("d-M-y");
                        $activity['activity_by'] = $users[Auth::user()->id];
                        $activity_arr[] = $activity;

                    }
                }

        }
        // 

       

        if(!empty($bugData)){

            \App\Models\Bug::where('id','=',$id)
            ->update([
                'bug_code' => $bugData['bug_code'],
                'project_id' => $bugData['project_id'],
                'sprint_id' => $bugData['sprint_id'],
                'status_id' => $bugData['status_id'],
                'severity_id' => $bugData['severity_id'],
                'reported_by' => $bugData['reported_by'],
                'assigned_to' => $bugData['assigned_to'],
                'description' => $bugData['description'],
                'title' => $bugData['title']
            ]);

            if(!empty($activity_arr)){
                 Activity::insert($activity_arr);
            }
            
        }

        // if(!empty($bugData['comment'])){
        //     BugComment::create([
        //         'bug_id' => $id,
        //         'comment' => $bugData['comment'],
        //         'created_by' => Auth::user()->id
        //     ]);
        // }

    if (!empty($files)) {
             foreach ($files as $key => $value) {
                $path = $value->store('BugDocuments');
                $name = $value->getClientOriginalName();
                $docs_paths[$key]['original_name'] = $name;
                $docs_paths[$key]['path'] = $path;
                $docs_paths[$key]['unique_name'] = basename($path);
            }

            //dd($docs_paths);
            foreach ($docs_paths as $key => $fileDetails) {
                $insert[] = [
                    'bug_id' =>$id,
                    'unique_name'=>$fileDetails['unique_name'],
                    'name'=>$fileDetails['original_name'],
                    'path'=>$fileDetails['path']
                ];
            }

            //dd($insert);
            \App\Models\BugDoc::insert($insert);
        }

        $bugMailData['bug_code'] = $bugData['bug_code'];
        $bugMailData['title'] =  $bugData['title'];
        $projectName = Project::select('name')->find($bugData['project_id']);
        $projectName = $this->objToArray($projectName);
        $bugMailData['project'] = $projectName['name'];
        $sprintName = Sprint::select('name')->find($bugData['sprint_id']);
        $sprintName = $this->objToArray($sprintName);
        $bugMailData['sprint'] = $sprintName['name'];
        $email_assigned = \App\User::select('email','first_name','last_name')->find($bugData['assigned_to']);
        $email_assigned = $this->objToArray($email_assigned);
        $to_email = $email_assigned['email'];
        $bugMailData['assigned_to'] =  $email_assigned['first_name']." ".$email_assigned['last_name'];
        $bug_status = BugStatus::select('name')->find($bugData['status_id']);
        $bug_status = $this->objToArray($bug_status);
        $bug_severity = Severity::select('name')->find($bugData['severity_id']);
        $bug_severity = $this->objToArray($bug_severity);
        $bugMailData['severity'] = $bug_severity['name'];
        $bugMailData['status'] =  $bug_status['name'];
        $bugMailData['assigned_by'] = Auth::user()->first_name." ".Auth::user()->last_name;
        $bugMailData['bug_id'] = $id;
        $bugMailData['subject'] = "BG-".$bugData['bug_code'].",".$bugData['title'];

        if($old_data->status_id != $bugData['status_id']){
            $status = BugStatus::whereIn('id',[$old_data->status_id,$bugData['status_id']])->pluck('name','id');
            $bugMailData['old_status'] = $status[$old_data->status_id];
            $bugMailData['new_status'] = $status[$bugData['status_id']];
        } else {
            $bugMailData['old_status'] = null;
            $bugMailData['new_status'] = null;
        }

        Mail::to($to_email)->send(new BugNotification($bugMailData));


        return 1;

        //return redirect()->route('/viewBug', ['id' => $id]);
    }

    protected function _checkForPermission($options = []) {
        $id = data_get($options, 'id');
        $access_type = data_get($options, 'access_type');

        $bugData = $this->bugData = \App\Models\Bug::where('id','=',$id)->first();
        if (empty($bugData)) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid Request.'
            ]);
        }

        $has_permission = isUserHasActionPermission([
            'module_id' => config('bug.bug_module_id'),
            'action' => $access_type, // view_access, edit_access, delete_access
            'modelObj' => $bugData
        ]);

        if (!$has_permission) {
            if (request()->expectsJSON()) {
                return response()->json([
                    'success' => 0,
                    'message' => config('bug.no_permission_to_access_msg')
                ]);
            }

            return redirectWithFlashMsg([
                'url' => url('/viewBugs'),
                'type' => 'danger',
                'message' => config('bug.no_permission_to_access_msg')
            ]);

        }

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $bugData = \App\Models\Bug::where('id','=',$id)->first();
        $permissionResponse = $this->_checkForPermission([
            'access_type' => 'delete_access',
            'id' => $id
        ]);

        if ($permissionResponse !== true) {
            return $permissionResponse;
        }

        if ($this->bugData->delete()) {
            return response()->json([
                'success' => 1,
                'message' => 'Record has been deleted successfully.'
            ]);
        }
    }

    public function viewBugs(Request $request){

        $limit = $request->get('limit');
        if (empty($limit)) {
            $limit = 10;
        }

        $projects = $this->projects();
        $modules  = $this->modules();
        $sprints  = $this->sprints();
        $status   = $this->status();
        $severity = $this->severity();
        $users    = $this->users();
        $statusObj =  \App\Models\BugStatus::select('id','name AS label')->get();


        // $bugs = $this->updateBugs($bugs);
        $userID = Auth::user()->id;
        $defaultProject = \App\Models\DefaultValue::select('relation_id')->where([['user_id','=',$userID],['relation_type','=','Project']])->first();
        $defaultProject = $this->objToArray($defaultProject);

        $allActiveProjects = project::select('id','name')->where('status','=',1)->get();
        
        $permissions_list_arr = [];
        foreach (config('bug.permissions_list') as $permission) {
            $permissions_list_arr[$permission] = isUserHasActionPermission([
                'module_id' => config('bug.bug_module_id'),
                'action' => $permission, // view_access, edit_access, delete_access
                'get_access_type' => true
            ]);
        }
        // $allActiveProjects = $this->objToArray($allActiveProjects);

        return view('bugsListView',compact('bugData', 'permissions_list_arr', 'projects','modules','sprints','status','severity','users','allActiveProjects','defaultProject','statusObj'));
    }

    /*public function bugsViewFilter(Request $request){
        $filterConditions = array_filter($request->all());

        $bugData = \App\Models\Bug::select(config('bug.fields_list_excluding_description'))->where($filterConditions)->paginate(10);
        $bugData->getCollection()->transform(function($bug) {
            foreach (config('bug.permissions_list') as $permission) {
                $bug->{$permission} = isUserHasActionPermission([
                    'module_id' => config('bug.bug_module_id'),
                    'action' => $permission, // view_access, edit_access, delete_access
                    'modelObj' => $bug
                ]);
            }
            return $bug;
        });

        $bugData = $this->objToArray($bugData);
        // dd($bugData);
        $bugData = $this->updateBugs($bugData['data']);
        return response()->json($bugData);
    }*/

    public function setDefaultProject($id){

        $userID = Auth::user()->id;
        $is_duplicated = \App\Models\DefaultValue::where('user_id','=',$userID)->select('relation_id','user_id')->first();
        $is_duplicated = $this->objToArray($is_duplicated);

        if(sizeof($is_duplicated) == 0){
            \App\Models\DefaultValue::create([
                'user_id'=>Auth::user()->id,
                'relation_id'=>$id,
                'relation_type'=>'Project'
            ]);
        }else {
            \App\Models\DefaultValue::where('user_id','=',$is_duplicated['user_id'])->where('relation_type','=','Project')->update([
                'relation_id'=>$id
            ]);
        }

        
   }

   public function saveComment(Request $request){
        $data = $request->all();

        if (!empty($data)) {           
            $is_inserted = BugComment::create([
                'bug_id' => $data['bug_id'],
                'comment' => $data['text'],
                'created_by' => Auth::user()->id
            ]);

            if($is_inserted){
                $comments = BugComment::where('bug_id',$data['bug_id'])->get();
                return response()->json([
                    'success' =>1,
                    'message' =>'Comment saved successfully',
                    'comments'=>$comments
                ]);
            }
        }
   }

   public function deleteFile($id){
       $is_deleted = \App\Models\BugDoc::find($id)->delete();
       if($is_deleted){
         $name = \App\Models\BugDoc::select('unique_name')->find($id);
         Storage::delete($name);

         return response()->json([
             'success' =>1,
             'message' =>'File deleted Successfully'
         ]);



     } else {

         return response()->json([
             'success' =>0,
             'message' =>'Something went wrong'
         ]);

     }
   }

    public function bugsViewFilter(Request $request){

        $filterConditions = array_filter($request->all());
        $filterColumns = ['project_id', 'sprint_id', 'module_id', 'severity_id', 'status_id', 'assigned_to','reported_by'];
        $filterConditions = [];
        foreach ($filterColumns as $filter_column) {
            $filter_value = $request->get($filter_column);
            if (!empty($filter_value)) {
                $filterConditions[$filter_column] = $filter_value;
            }
            if ($filter_column == 'project_id') {
                $user_id = auth()->user()->id;

                if (!empty($user_id)) {
                    $default_project = getDefaultProject();

                    if (!empty($default_project)) {
                        $filterConditions[$filter_column] = $default_project;
                    }
                }
            }
        }

        $view_access = getUserPermissionAccess([
            'module_id' => config('bug.bug_module_id'),
            'action' => 'view_access', // view_access, edit_access, delete_access
        ]);

        if ($view_access === 2) {
            $filterConditions['assigned_to'] = auth()->user()->id;
        }



        $bugQuery = \App\Models\Bug::select(config('bug.fields_list_excluding_description'));
      
        foreach ($filterConditions as $column => $filter_value) {
            if (is_array($filter_value)) {
                $bugQuery->whereIn($column, $filter_value);
            } else if (is_string($filter_value)) {
                $bugQuery->where($column, $filter_value);
            }

        }
        
        $bugData = $bugQuery->where('project_id','=',$default_project)->orderBy('created_at', 'desc')->paginate(10);

        $filtered_bug_ids = $bugQuery->orderBy('created_at', 'desc')->pluck('id');

        $request->session()->put('filtered_bug_ids',$filtered_bug_ids);

        $permissions_list_arr = [];
        
        $bugData->getCollection()->transform(function($bug) use(&$permissions_list_arr) {
            foreach (config('bug.permissions_list') as $permission) {
                $permission_val = $bug->{$permission} = isUserHasActionPermission([
                    'module_id' => config('bug.bug_module_id'),
                    'action' => $permission, // view_access, edit_access, delete_access
                    'modelObj' => $bug
                ]);
                $permissions_list_arr[$permission] = $permission_val;
                
            }

            $bug = $this->updateBugObject($bug);
            // dd($bug);
            // dd($bug->toArray());
            return $bug;
        });
        // dd($bugData->getCollection()->toArray());

        // $bugData = $this->objToArray($bugData);
        // dd($bugData);
        // $bugData = $this->updateBugs($bugData['data']);
        return response()->json($bugData);
    }

    public function nextBug(Request $request){

        $id = $request->get('bugId');
        $type = $request->get('type');

        if($request->session()->has('filtered_bug_ids')){
            $bugIds = $request->session()->get('filtered_bug_ids');
            $bugIds = $this->objToArray($bugIds);
            $current_bug_position_in_bugIds = array_search($id, $bugIds);
            // dd($current_bug_position_in_bugIds);
            if ($type == 'next') {                
                if (array_key_exists($current_bug_position_in_bugIds+1, $bugIds)) {
                    $nextBugID = $bugIds[$current_bug_position_in_bugIds+1];                   
                } else {
                    $nextBugID = $bugIds[0]; 
                }
            } else { 
                if (array_key_exists($current_bug_position_in_bugIds-1, $bugIds)) {
                    $nextBugID = $bugIds[$current_bug_position_in_bugIds-1];                   
                } else {
                    $nextBugID = $bugIds[sizeof($bugIds)-1]; 
                }  
            }
            return redirect('/viewBug/'.$nextBugID);
        }
    }

    protected function updateBugObject($bugObj){

        if (isset($this->projectsArr)) {
            $projects = $this->projectsArr;
        } else {
            $projects = $this->projectsArr = $this->projects();
        }

        if (isset($this->modulesArr)) {
            $modules = $this->modulesArr;
        } else {
            $modules = $this->modulesArr  = $this->modules();
        }

        if (isset($this->sprintsArr)) {
            $sprints = $this->sprintsArr;
        } else {
            $sprints = $this->sprintsArr  = $this->sprints();
        }

        if (isset($this->statusArr)) {
            $status = $this->statusArr;
        } else {
            $status = $this->statusArr  = $this->status();
        }

        if (isset($this->severityArr)) {
            $severity = $this->severityArr;
        } else {
            $severity = $this->severityArr  = $this->severity();
        }

        if (isset($this->usersArr)) {
            $users = $this->usersArr;
        } else {
            $users = $this->usersArr  = $this->users();
        }

        $bugObj->project_id = data_get($projects, $bugObj->project_id, '-');
        $bugObj->module_id = data_get($modules, $bugObj->module_id, '-');
        $bugObj->sprint_id = data_get($sprints, $bugObj->sprint_id, '-');
        //$bugObj->status_id = data_get($status, $bugObj->status_id, '-');
        $bugObj->severity_id = data_get($severity, $bugObj->severity_id, '-');
        $bugObj->reported_by = data_get($users, $bugObj->reported_by, '-');
        $bugObj->assigned_to = data_get($users, $bugObj->assigned_to, '-');
        $bugObj->created_by = data_get($users, $bugObj->created_by, '-');

        // if (!empty($bugObj->created_at)) {
        //     // dd($bugObj->created_at->format('M d Y'));
        //     $bugObj->created_at = $bugObj->created_at->format('M d Y');
        // }
        //
        // if (!empty($bugObj->updated_at)) {
        //     // dd($bugObj->updated_at->format('M d Y'));
        //     $bugObj->updated_at = $bugObj->updated_at->format('M d Y');
        // }
        //
        // if (!empty($bugObj->resolved_on)) {
        //     $bugObj->resolved_on = $bugObj->resolved_on->format('M d Y');
        // }

        return $bugObj;
    }

    public function deleteBug($id){

        $is_deleted = \App\Models\Bug::find($id)->delete();

        if($is_deleted){
            return response()->json([
                'success' => 1,
                'message' => 'Bug Deleted Succesfully'
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'Something Went Wrong'
            ]);
        }

    }



    // Protected functions

    protected function objToArray($getobject) {
        if (!empty($getobject)) {
            $getArray = $getobject->toArray();
        } else {
            $getArray = array();
        }

        return $getArray;
    }

    public function bulkDelete(Request $request){
            $deleteArray = array_keys(array_filter($request->all()));

            $is_deleted = \App\Models\Bug::whereIn('id',$deleteArray)->delete();

            if($is_deleted){
                return response()->json([
                    'success' => 1,
                    'message' => 'Bugs deleted Successfully'
                ]);
            } else {
                return response()->json([
                    'success' => 0,
                    'message' =>'Something went wrong'
                ]);
            }

    }

    public function changeProject(Request $request , $id){
      
        $deleteArray = array_keys(array_filter($request->all()));

        foreach ($deleteArray as $key => $value) {
           $is_deleted =  \App\Models\Bug::find($value)->update(['project_id' => $id]);        
        }

        return response()->json([
            'success' => 1,
            'message' =>'Project for bugs updated successfully'
        ]);
    }

    public function changeSprint(Request $request , $id){

        $deleteArray = array_keys(array_filter($request->all()));

        foreach ($deleteArray as $key => $value) {
           $is_deleted =  \App\Models\Bug::find($value)->update(['sprint_id' => $id]);        
        }

        return response()->json([
            'success' => 1,
            'message' =>'Project for bugs updated successfully'
        ]);
    }

    public function changeStatus(Request $request){
        $data = $request->all();
        $is_status_updated = \App\Models\Bug::where('id',$data['bug_id'])->update(['status_id'=>$data['status_id']]);

        if ($is_status_updated) {
           return response()->json([
                'success' => 1,
                'message' => 'Status updated successfully'
           ]); 
        }
    }

    public function searchBugs(Request $request){
        $key = $request->get('keyword');
        $bugs = \App\Models\Bug::where('title','LIKE','%'.$key.'%')->orWhere('bug_code',$key)
                ->selectRaw('id,bug_code, CONCAT(" BG-",bug_code," ,",title) as title' )->get();

                // selectRaw('id,CONCAT(first_name," ",last_name) as full_name')

        if (!empty($bugs)) {
            $bugs = $bugs->transform(function($bug) {
                foreach (config('bug.permissions_list') as $permission) {
                    $bug->{$permission} = isUserHasActionPermission([
                        'module_id' => config('bug.bug_module_id'),
                        'action' => $permission, // view_access, edit_access, delete_access
                        'modelObj' => $bug
                    ]);
                }

                if (!empty($bug->view_access)) {
                    return $bug;
                }
                // $bug = $this->updateBugObject($bug);
            });
        }

        return response()->json($bugs);
    }

        public function searchbugsList($key){
            $bugs = \App\Models\Bug::where('title','LIKE','%'.$key.'%')
                        ->orWhere('bug_code',$key)
                        ->select(config('bug.fields_list_excluding_description'))->get();

            if (!empty($bugs)) {
                $bugs = $bugs->transform(function($bug) {
                    foreach (config('bug.permissions_list') as $permission) {
                        $bug->{$permission} = isUserHasActionPermission([
                            'module_id' => config('bug.bug_module_id'),
                            'action' => $permission, // view_access, edit_access, delete_access
                            'modelObj' => $bug
                        ]);
                    }

                    if (!empty($bug->view_access)) {
                        $bug = $this->updateBugObject($bug);
                        return $bug;
                    }
                    // 
                });
            }

            return response()->json($bugs);


         }

    protected function projects(){
        $projects = \App\Models\project::where('status','=',1)->select('id','name')->pluck('name','id');
        $projects = $this->objToArray($projects);
        return $projects;
    }

    protected function modules(){
        $modules  = \App\Models\Module::pluck('name','id');
        $modules  = $this->objToArray($modules);
        return $modules;
    }

    protected function sprints(){
        $sprints  = \App\Models\Sprint::pluck('name','id');
        $sprints  = $this->objToArray($sprints);
        return $sprints;
    }

    protected function status(){
        $status   = \App\Models\BugStatus::pluck('name','id');
        $status   = $this->objToArray($status);
        return $status;
    }

    protected function severity(){
        $severity = \App\Models\Severity::pluck('name','id');
        $severity = $this->objToArray($severity);
        return $severity;
    }

    protected function users(){
        $users    = \App\User::selectRaw('id,CONCAT(first_name," ",last_name) as full_name')->pluck('full_name','id');
        $users    = $this->objToArray($users);
        return $users;
    }

    protected function downloadFile($id){
        $path = \App\Models\BugDoc::select('path','name')->where('id','=',$id)->first();

        if(file_exists(storage_path("/app/".$path->path))) {
               return Redirect::to("storage//app/".$path->path);
               // return response()->download(storage_path("/app/".$path->path),$path->name);          
        } else {
            return back();
        }
    }


    public function bugActivity($id)
    {
        $activities = Activity::where('bug_id',$id)->get();
        return response()->json($activities);

    }
    protected function updateBugs($bugs){

        $projects = $this->projects();
        //dd($projects);
        $modules  = $this->modules();
        $sprints  = $this->sprints();
        $status   = $this->status();
        $severity = $this->severity();
        $users    = $this->users();
        //print_r($users);

        foreach ($bugs as $key => $bug) {
            if(is_array($bug)){
                foreach ($bug as $columnName => $value) {
                    // echo $bugs[$key][$columnName];
                    switch ($columnName) {
                        case 'project_id':
                                        if($value == null) {
                                            $bugs[$key][$columnName] = '-';
                                        }
                                        else {
                                            $bugs[$key][$columnName] = isset($projects[$value]) ? $projects[$value] : null;
                                        }
                                        break;
                        case 'module_id':
                                        if($value == null)
                                            $bugs[$key][$columnName] = '-';
                                        else
                                            $bugs[$key][$columnName] = $modules[$value];
                                        break;
                        case 'sprint_id':
                                        if($value == null)
                                            $bugs[$key][$columnName] = '-';
                                        else
                                            $bugs[$key][$columnName] = $sprints[$value];
                                        break;
                        case 'status_id':
                                        if($value == null)
                                            $bugs[$key][$columnName] = '-';
                                        else
                                            $bugs[$key][$columnName] = $status[$value];
                                        break;
                        case 'severity_id':
                                        if($value == null)
                                            $bugs[$key][$columnName] = '-';
                                        else
                                            $bugs[$key][$columnName] = $severity[$value];
                                        break;
                        case 'reported_by':
                                        if($value == null)
                                            $bugs[$key][$columnName] = '-';
                                        else
                                            //dd($value);
                                            $bugs[$key][$columnName] = $users[$value];
                                        break;
                        case 'assigned_to':
                                        if($value == null)
                                            $bugs[$key][$columnName] = '-';
                                        else
                                        $bugs[$key][$columnName] = $users[$value];
                                        break;
                        case 'resolved_on':
                                        if($value == null)
                                            $bugs[$key][$columnName] = '-';
                                        else
                                            $bugs[$key][$columnName] = $users[$value];
                                        break;
                        case 'created_at':
                                        if($value == null)
                                            $bugs[$key][$columnName] = '-';
                                        else
                                            $created_at = Carbon::parse($value);
                                            $bugs[$key][$columnName] = $created_at->format('M d Y');;
                                        break;
                        case 'updated_at':
                                        if($value == null)
                                            $bugs[$key][$columnName] = '-';
                                        else
                                            $updated_at = Carbon::parse($value);
                                            $bugs[$key][$columnName] = $updated_at->format('M d Y');;
                                        break;
                    }
                }
            }
        }

        return $bugs;
    }


}
