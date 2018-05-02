<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use \App\Models\Role;
use \App\Models\PasswordToken;
use \App\Mail\UserPasswordCreation;
use Illuminate\Support\Facades\Mail;
use Auth;

class UsersController extends Controller
{

    public function __construct() {
        $this->middleware('auth',['except'=>['activateUser','savePassword','forgotPassword','sendMail']]);
    }


    public function viewUsers(Request $request){

        $usersData = User::where('admin',0)->select('id','first_name','last_name','email','status','role_id')->paginate(10);
        $users = $this->objToArray($usersData);
        $users = $users['data'];

        $roles = Role::select('id','name')->get();
        $rolesArr = Role::select('id','name')->pluck('name','id');

        $roles = $this->objToArray($roles);

        $permissions_list_arr = [];
        foreach (config('bug.permissions_list') as $permission) {
            $permissions_list_arr[$permission] = isUserHasActionPermission([
                'module_id' => config('bug.bug_module_id'),
                'action' => $permission, // view_access, edit_access, delete_access
                'get_access_type' => true
            ]);
        }

        return view('users',compact('users','usersData','roles','permissions_list_arr','rolesArr'));
    }

    public function addUser(Request $request){
        $data = $request->all();

         // dd($data);
        if(!array_key_exists('is_admin', $data)){
            $data['is_admin'] = false;
        }

        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $email = $data['email'];
        $role_id = $data['role_id'];

        $is_email_duplicated = User::select('id')->where('email','LIKE',$email)->get();

        if(sizeof($is_email_duplicated) > 0){
            return response()->json([
                'success' => 2,
                'message' =>'Email already exists'
            ]);
        }
        


        $user = User::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'role_id' => $role_id
        ]);

        if ($user) {

            if($data['is_admin'] == true){
                User::where('id','=',$user->id)->update(['is_admin' => 1]);
            }

        $userData['name'] =  $first_name." ".$last_name;
        $userData['appName'] = 'Bug Tracker';
        $userData['userID'] = $user->id;
        $userData['token'] = str_random(40);

        PasswordToken::insert([
            'email'=> $email,
            'token'=> $userData['token']
        ]);

        Mail::to($email)->send(new UserPasswordCreation($userData));




            return response()->json([
                'success'=>1,
                'message'=>'User created successfully'
            ]);
        } else {
            return response()->json([
                'success'=>0,
                'message'=>'Something went wrong'
            ]);
        }


    }

    public function resendEmail($id){
        
        $user   = User::select('email','first_name','last_name')->find($id);

        $tokenObj  = PasswordToken::select('token')->where('email','LIKE',$user->email)->first();

        $userData['name']    =   $user->first_name." ".$user->last_name;
        $userData['appName'] =   'Bug Tracker';
        $userData['userID']  =   $id;
        if(empty($tokenObj->token)){
            $userData['token']   =   str_random(40);
            PasswordToken::create([
                'email'=> $user->email,
                'token' => $userData['token'] 
            ]);
        }else{
            $userData['token'] = $tokenObj->token;
        }
        

        Mail::to($user->email)->send(new UserPasswordCreation($userData));

        if(!Mail::failures()){
            return response()->json([
                'success' =>1,
                'message' =>'Mail Sent Successfully'
            ]);
        }


    }

    public function getUsers(){
        $usersData = User::where('admin',0)->select('id','first_name','last_name','email','status','role_id')->paginate(10);
        $users = $this->objToArray($usersData);
        $users = $users['data'];

        $returnUsers['usersData'] = $usersData;
        $returnUsers['users'] = $users;

        return $returnUsers;
    }


    public function viewUser($id){
        $user = User::where('id','=',$id)->first();

        $user = $this->objToArray($user);

        return $user;

    }

    public function updateUser(Request $request){
        $data = $request->all();

        $userID = $data['id'];

        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $email = $data['email'];
        $role_id = $data['role_id'];

        $role = User::where('id','=',$userID)->update([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'role_id' => $role_id
        ]);



        if ($role) {

            if($data['is_admin'] == 1){
                User::where('id','=', $userID )->update(['is_admin' => 1]);
            } 

            if($data['is_admin'] == 0){
                User::where('id','=', $userID )->update(['is_admin' => 0]);
            }

            return response()->json([
                'success'=>1,
                'message'=>'User Updated successfully'
            ]);
        } else {
            return response()->json([
                'success'=>0,
                'message'=>'Something went wrong'
            ]);
        }

    }


    public function activateUser($id){
        $token = request('token');

        $is_token_valid = PasswordToken::select('id')->where('token','LIKE',$token)->first();

        if(sizeof($is_token_valid) > 0){
            return view('CreatePassword', compact('id'));
        } else {
            return view('link_expired');
        }      
       
    }

    public function savePassword(Request $request,$id){
        $data = $request->all();

        $password = $data['password'];

        // $email = \App\User::select('email')->where('id','=',$id)->first();
        // var_dump($email->email);
        // dd();

        $userPassword = User::where('id','=',$id)->update([
                            'password' => bcrypt($password),
                            'status' => 1
                        ]);

        if ($userPassword) {

            $email = \App\User::select('email')->where('id','=',$id)->first();

            $is_token_nullified = PasswordToken::where('email','LIKE',$email->email)->update(['token'=>null]);


            return response()->json([
                'success'=>1,
                'message'=>'password Updated successfully'
            ]);
        } else {
            return response()->json([
                'success'=>0,
                'message'=>'Something went wrong'
            ]);
        }
    }

    public function userProfile($id){
        $data = User::select('id','first_name','last_name','email')->where('id','=',$id)->first();
        $data = $this->objToArray($data);
        //dd($data);
        return view('view_profile',compact('data'));
    }

    public function profileUpdate(Request $request){
        $data = $request->all();
        $userID = Auth::user()->id;
        $profile = User::where('id','=',$userID)
                        ->update([
                            'first_name' => $data['first_name'],
                            'last_name' => $data['last_name'],
                            'email'=> $data['email']
                        ]);
        if($profile){
            return response()->json([
                'success'=>1,
                'message'=>'Profile Updated successfully'
            ]);
        } else {
            return response()->json([
                'success'=>0,
                'message'=>'Something went wrong'
            ]);
        }
    }

    public function changePassword(){
        return view('change_password');
    }

    public function updateNewPassword(Request $request){
        $data = $request->all();
        $userID = Auth::user()->id;

        $result = User::where('id','=',$userID)->update(['password'=>bcrypt($data['password'])]);

        if($result){
            return response()->json([
                'success'=>1,
                'message'=>'Password Updated successfully'
            ]);
        } else {
            return response()->json([
                'success'=>0,
                'message'=>'Something went wrong'
            ]);
        }

    }

    public function deleteUser($id){
        $is_deleted = User::find($id)->delete();

        if($is_deleted){
            return response()->json([
                'success'=>1,
                'message'=>'User deleted successfully'
            ]);
        } else {
            return response()->json([
                'success'=>0,
                'message'=>'Something went wrong'
            ]);
        }
    }


    /*public function forgotPassword(){
        return view('email');
    }*/

    /*public function sendMail($email){         
       if(Mail::to($email)->send(new ResetPassword())){
            return response
       }
    }*/

    protected function objToArray($getobject) {
        if (!empty($getobject)) {
            $getArray = $getobject->toArray();
        } else {
            $getArray = array();
        }

        return $getArray;
    }
}
