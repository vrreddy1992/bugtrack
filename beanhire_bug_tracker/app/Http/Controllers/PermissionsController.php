<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\ProjectModule;
use \App\Models\Permission;
use \App\Models\Role;
use \App\User;
use Illuminate\Support\Facades\DB;
use Auth;

class PermissionsController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function view(){

        $roles_list = Role::all();
        $roles_list = $this->objToArray($roles_list);

        $users = User::select([DB::raw("CONCAT(first_name, ' ', last_name) AS name"),'id'])->pluck('name','id');
        $users = $this->objToArray($users);

        $modules = ProjectModule::all();
        $modules = $this->objToArray($modules);

        $permissions_list_arr = [];
        foreach (config('bug.permissions_list') as $permission) {
            $permissions_list_arr[$permission] = isUserHasActionPermission([
                'module_id' => config('bug.bug_module_id'),
                'action' => $permission, // view_access, edit_access, delete_access
                'get_access_type' => true
            ]);
        }

        //dd($modules);
        return view('security_by_role',compact('roles_list','users','modules','permissions_list_arr'));
    }

    public function getRoles(){
        $roles_list = Role::all();
        $roles_list = $this->objToArray($roles_list);

        return response()->json($roles_list);
    }

    public function deleteRole($id){

        $is_deleted = Role::find($id)->delete();

        if($is_deleted){
            Permission::where('role_id','=',$id)->delete();
            return response()->json([
                'success'=>1,
                'message'=>'Role deleted successfully'
            ]);
        } else {
            return response()->json([
                'success'=>0,
                'message'=>'Something went wrong'
            ]);
        }
    }

    public function setPermission(Request $request){
        $data = $request->all();
        //dd($data);
        $roleID = $data['roleID'];
        $roleModules = $data['crud']['role_modules'];
        $moduleID = $data['crud']['id'];

        $view_access = $roleModules['view_access'];
        $edit_access = $roleModules['edit_access'];
        $delete_access = $roleModules['delete_access'];


        //dd($roleModules);

        $is_updated = Permission::where([['role_id','=',$roleID],['module_id','=',$moduleID]])
        ->update([
            'view_access'=>$view_access,
            'edit_access'=>$edit_access,
            'delete_access'=>$delete_access
        ]);

        if ($is_updated) {
            return response()->json([
                'success'=>1,
                'message'=>'Permission set Successfully'
            ]);
        } else {
            return response()->json([
                'success'=>0,
                'message'=>'Something went wrong'
            ]);
        }
        // array:2 [  "crud" => array:3 [    "id" => 2    "name" => "Projects"    "role_modules" => array:3 [      "view_access" => 1      "edit_access" => 2      "delete_access" => 0    ]  ]
        //  "roleID" => 1]


    }

    public function getPermissions($roleID){

        $modules = ProjectModule::all();
        $modules = $this->objToArray($modules);

        foreach ($modules as $key => $module) {

            $permission = Permission::select('view_access','edit_access','delete_access')->where([['role_id','=',$roleID],
            ['module_id','=',$module['id']]])->first();
            $permission = $this->objToArray($permission);
            $modulePermissions[$module['name']] = $permission;
            $modules[$key]['role_modules'] = $permission;
        }
        return response()->json($modules);
}

    public function addRole(Request $request){
        $data = $request->all();


        if (!array_key_exists('similarTo', $data)) {
            $similarTo = null;
        } else {
            $similarTo = $data['similarTo'];
        }

        $roleName = $data['name'];
        $comments = $data['comments'];


        $role = Role::create([
            'name'=>$roleName,
            'comments'=>$comments,
            'created_by'=>Auth::user()->id,
            'updated_by'=>Auth::user()->id
        ]);

        $roleID = $role->id;

        $modules = ProjectModule::pluck('id');
        $modules = $this->objToArray($modules);

        if ($similarTo == null) {

            foreach ($modules as $key => $module_id) {
                $permissions[] = [
                    'role_id'=>$roleID,
                    'module_id'=>$module_id,
                    'view_access'=>0,
                    'edit_access'=>0,
                    'delete_access'=>0
                ];
            }

            if (permission::insert($permissions)) {
                return response()->json([
                    'success'=>1,
                    'message'=>'Role created successfully'
                ]);
            } else {
                return response()->json([
                    'success'=>0,
                    'message'=>'Something went wrong'
                ]);
            }

        } else {

            foreach ($modules as $key => $module_id) {
                $similarTo_permissions = Permission::select('module_id','view_access','edit_access','delete_access')
                                         ->where([['role_id','=',$similarTo],['module_id','=',$module_id]])->get();

                $similarTo_permissions_array[] = $this->objToArray($similarTo_permissions);
            }

            foreach ($similarTo_permissions_array as $key => $permission) {
                if (is_array($permission)) {
                    foreach ($permission as $permission_key => $permission_value) {
                        $similarTo_permissions_array[$key][$permission_key]['role_id'] = $roleID;
                        $final_permissions[] = $similarTo_permissions_array[$key][$permission_key];
                    }
                }
            }


            if (permission::insert($final_permissions)) {
                return response()->json([
                    'success'=>1,
                    'message'=>'Role created successfully'
                ]);
            } else {
                return response()->json([
                    'success'=>0,
                    'message'=>'Something went wrong'
                ]);
            }


        }




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
