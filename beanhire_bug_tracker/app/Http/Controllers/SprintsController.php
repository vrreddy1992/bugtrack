<?php

namespace App\Http\Controllers;

use \App\Models\Sprint;
use \App\Models\SprintStatus;
use \App\Models\Project;
use \App\Models\Bug;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class SprintsController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //dd($id);
        $sprints = Sprint::where('project_id','=',$id)->get();
        
        $sprints_arr = [];
        if (!empty($sprints)) {
            $sprints_arr = $sprints->pluck('name', 'id')->toArray();
        }
        // dd($sprints_arr);
        // $sprints = $this->objToArray($sprints);

        return response()->json($sprints_arr);

    }

    public function projectSprints($id)
    {
      
        $sprints = Sprint::where('project_id','=',$id)->get();
        return response()->json($sprints);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sprintData = $request->all();

        $project_dates = Project::where('id','=',$sprintData['project_id'])->select('start_date','end_date')->first();

        $sprintData['project_start'] = $project_dates->start_date;
        $sprintData['project_end'] = $project_dates->end_date;

        $start_date = Carbon::parse($sprintData['start_date']);
        $end_date   = Carbon::parse($sprintData['end_date']);
        if (array_key_exists('completed_date', $sprintData)) {
             $completed_date = Carbon::parse($sprintData['completed_date']);
        } else {
             $completed_date = null;
        }


        $valitionResult = Validator::make($sprintData, [
            'start_date' => 'required|date|after_or_equal:project_start',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:project_end',
            'completed_date'=>'date|after:start_date|after:end_date',

        ]);

       if($valitionResult->fails()){
           return response()->json(['error'=>$valitionResult->errors()->all()]);
       }


        if(!array_key_exists('completed_date', $sprintData)){
            $sprintData['completed_date'] = null;
        }

        Sprint::create([
            'project_id'=>$sprintData['project_id'],
            'name' => $sprintData['name'],
            'status' => $sprintData['status'],
            'start_date' => $start_date,
            'end_date' => $end_date,
            'completed_date' => $completed_date,
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Sprint created successfully.'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sprintData = Sprint::where('id','=',$id)->first();
        $sprintData = $this->objToArray($sprintData);

        return response()->json($sprintData);
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
         $sprintData = $request->all();

         $project_dates = Project::where('id','=',$sprintData['project_id'])->select('start_date','end_date')->first();

         $sprintData['project_start'] = $project_dates->start_date;
         $sprintData['project_end'] = $project_dates->end_date;

         $start_date = Carbon::parse($sprintData['start_date']);
         
         $end_date   = Carbon::parse($sprintData['end_date']);
         if (array_key_exists('completed_date', $sprintData)) {
              $completed_date = Carbon::parse($sprintData['completed_date']);
         } else {
              $completed_date = null;
         }


         $valitionResult = Validator::make($sprintData, [
             'start_date' => 'required|date|after_or_equal:project_start',
             'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:project_end',
             'completed_date'=>'date|after:start_date|after:end_date',

         ]);

        if($valitionResult->fails()){
            return response()->json(['error'=>$valitionResult->errors()->all()]);
        }


         if(!array_key_exists('completed_date', $sprintData)){
             $sprintData['completed_date'] = null;
         }

         Sprint::where('id',$id)->update([
             'project_id'=>$sprintData['project_id'],
             'name' => $sprintData['name'],
             'status' => $sprintData['status'],
             'start_date' => $start_date,
             'end_date' => $end_date,
             'completed_date' => $completed_date,
         ]);

         return response()->json([
             'success' => 1,
             'message' => 'Sprint updated successfully.'
         ]);
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

    public function deleteSprint($id){

        //

        $bugs_sprint = Bug::select('id')->where('sprint_id','=',$id)->get();

        if (sizeof($bugs_sprint) == 0) {

                $is_deleted = Sprint::find($id)->delete();

                if($is_deleted){
                    return response()->json([
                        'success' => 1,
                        'message' => 'Sprint deleted succesfully'
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
                    'message' => 'Please delete the bugs in this sprint then delete the sprint '
                ]);
        }


    }

    public function getStatues(){

        $statues = SprintStatus::get();
        $statues  = $this->objToArray($statues);

        $projects = Project::get();
        $projects = $this->objToArray($projects);

        $currentProject = getDefaultProject();

        $responseArray[] = $statues;
        $responseArray[] = $projects;
        $responseArray[] = $currentProject;

        return response()->json($responseArray);
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
