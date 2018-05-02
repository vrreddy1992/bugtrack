<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/addBug',  'BugsController@index');

Route::post('/savebug','BugsController@create');

Route::get('/viewBugs','BugsController@viewBugs');

Route::post('/bugsListViewFilter','BugsController@bugsViewFilter');

Route::get('/bugsListViewFilter','BugsController@bugsViewFilter');

Route::get('/editBug/{id}','BugsController@edit');

Route::post('/updateBug/{id}','BugsController@update');


// Route::get('/viewBug/{id}','BugsController@show');
Route::get('/viewBug/{id}','BugsController@show');

Route::get('/nextBug','BugsController@nextBug');

Route::post('/saveComment','BugsController@saveComment');


Route::get('/deleteBug/{id}','BugsController@deleteBug');

Route::post('/deleteBugsBulk','BugsController@bulkDelete');

Route::get('/deletefile/{id}','BugsController@deleteFile');

Route::get('/downloadFile/{id}','BugsController@downloadFile');

//projects
Route::get('/projects', 'ProjectsController@index');

Route::get('/project/{id}','ProjectsController@project');

Route::get('/get_projects','ProjectsController@getProjects');


Route::post('/saveProject','ProjectsController@create');

Route::get('/viewProject/{id}','ProjectsController@show');

Route::post('/updateProject/{id}','ProjectsController@update');

Route::get('/projectStatus','ProjectsController@getProjectStatus');

Route::post('/setDefaultProject/{id}','BugsController@setDefaultProject');

Route::get('/deleteProject/{id}','ProjectsController@deleteProject');

//sprints
Route::get('/sprints/{id}','SprintsController@index');
Route::get('/project_sprints/{id}','SprintsController@projectSprints');


Route::get('/sprintStatues','SprintsController@getStatues');

Route::post('/addSprint','SprintsController@store');

Route::get('/viewSprint/{id}','SprintsController@show');

Route::get('/deleteSprint/{id}','SprintsController@deleteSprint');

Route::post('/updateSprint/{id}','SprintsController@update');




Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/validation',function(){
    return view('formvalidation');
});



//permissions

Route::get('/roles_permissions','PermissionsController@view');

Route::post('/set_permission','PermissionsController@setPermission');

Route::get('/getRolePermissions/{id}','PermissionsController@getPermissions');

Route::post('/addRole','PermissionsController@addRole');

Route::get('/getRoles','PermissionsController@getRoles');

Route::get('/deleteRole/{id}','PermissionsController@deleteRole');

//UsersController
Route::get('/view_users','UsersController@viewUsers');

Route::post('/add_user','UsersController@addUser');

Route::get('/get_users','UsersController@getUsers');

Route::get('/viewUser/{id}','UsersController@viewUser');

Route::post('/update_user/{id}','UsersController@updateUser');

Route::get('/activateUser/{id}','UsersController@activateUser');

Route::get('/deleteUser/{id}','UsersController@deleteUser');

Route::post('/savePassword/{id}','UsersController@savePassword');

Route::get('/change_password','UsersController@changePassword');

Route::post('/updateNewPassword','UsersController@updateNewPassword');

Route::get('/sendMail/{id}','UsersController@resendEmail');

//Profile
Route::get('/profile/{id}','UsersController@userProfile');
Route::post('/updateprofile','UsersController@profileUpdate');


//bulk

Route::post('/deleteBulk','BugsController@bulkDelete');

Route::post('/changeProject/{id}','BugsController@changeProject');

Route::post('/changeSprint/{id}','BugsController@changeSprint');

Route::get('changeStatus','BugsController@changeStatus');

Route::get('/search_bugs','BugsController@searchBugs');
Route::get('/searchbugsList/{key}','BugsController@searchbugsList');


Route::get('/activity/{id}','BugsController@bugActivity');

// Route::get('/forgotPassword','UsersController@forgotPassword');

// Route::get('/sendMail/{value}','UsersController@sendMail');

Route::get('/layout', function(){
	return view('layouts.template');
});



//TestCases
Route::get('/modules','ModulesController@view');

Route::get('/viewModule/{id}','ModulesController@viewModule');

Route::post('/update_module/{id}','ModulesController@update');

Route::get('deleteModule/{id}','ModulesController@delete');

Route::post('/add_modules','ModulesController@add');

Route::get('/getModule_defaultValues','ModulesController@getDefaultValues');

Route::get('/sub_modules/{id}','SubModulesController@view');

Route::get('/getModules','SubModulesController@getModules');

Route::post('/add_sub_module','SubModulesController@add');

Route::get('/add_testcase/{id}','TestCasesController@create');

Route::post('/store_testcase','TestCasesController@store');

Route::get('/testcases/{id}','TestCasesController@view');

Route::get('/getTestcases/{id}','TestCasesController@getTestcases');

Route::get('/editTestcase/{id}','TestCasesController@edit');

Route::post('/update_testcase/{id}','TestCasesController@update');












