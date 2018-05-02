<?php
function checkUserPermissions($role_id,$module_id){
    // return "helper function test";
}


// isUserHasActionPermission([
//     'role_id' => auth()->user()->role_id,
//     'module_id' => $module_id,
//     'action' => $action, // view_access, edit_access, delete_access
//     'action_type' => $action_type // all, own
// ]);

function getCurrentRouteControllerAndAction() {
    $currentAction = Route::currentRouteAction();
    if (strpos($currentAction, '@') === false) {
        return [
            'controller' => null,
            'action' => null
        ];
    }
    list($controller, $method) = explode('@', $currentAction);

    $controller = preg_replace('/.*\\\/', '', $controller);
    $controller = str_replace(['Controller', 'controller'], '', $controller);

    return [
        'controller' => $controller,
        'action' => $method
    ];
}

function getRolePermissions($module_id = null) {
    if (empty($module_id)) {
        return [];
    }

    $role_id = auth()->user()->role_id;
    $permissionsArr = [];
    if (!empty($role_id)) {
        $permissionsArr = \App\Models\Permission::where([
            'role_id' => $role_id,
            'module_id' => $module_id
        ])->get();
    }

    return convertCollectionToArray($permissionsArr);
}

function getDefaultProject() {
    $default_project = \App\Models\DefaultValue::select(['relation_id'])->where([
        'user_id' => Auth::user()->id,
        'relation_type' => 'Project'
    ])->first();
    if (empty($default_project)) {
        return null;
    }

    return $default_project->relation_id;
}

// convert
function convertCollectionToArray($collectionObj = null) {
   if ($collectionObj instanceof \Illuminate\Support\Collection) {
       return $collectionObj->toArray();
   }
   return $collectionObj;
}

function getUserPermissionAccess($options = []) {
    $module_id = data_get($options, 'module_id');
    $action = data_get($options, 'action');
    
    $user = auth()->user();
    if (empty($user)) {
        return false;
    }

    if (!empty($user->is_admin)) {
        return true;
    }
    
    $role_id = $user->role_id;

    $permission = \App\Models\Permission::where([
        'role_id' => $role_id,
        'module_id' => $module_id,
    ])->first();

    // echo "<pre>";print_r($permission);exit;

    if (!empty($permission)) {
        return $permission->$action;
    }

    return false;
}

function isUserHasActionPermission($options = []) {
    $user = auth()->user();
    if (empty($user)) {
        return false;
    }

    $role_id = $user->role_id;
    $module_id = data_get($options, 'module_id');
    $action = data_get($options, 'action');
    $modelObj = data_get($options, 'modelObj');

    if (!empty($user->is_admin)) {
        return true;
    }

    if (empty($role_id) || empty($module_id) || empty($action)) {
        return false;
    }

    if (!in_array($action, config('bug.permissions_list'))) {
        return false;
    }

    static $permissionArr = [];
    if (isset($permissionArr[$role_id][$module_id])) {
        $permission = $permissionArr[$role_id][$module_id];
    } else {
        $permission = \App\Models\Permission::where([
            'role_id' => $role_id,
            'module_id' => $module_id
        ])->first();
    }
    // echo '<pre>';print_r($permissionArr);

    $permissionArr[$role_id][$module_id] = $permission;

    // no access
    if (empty($permission->$action)) {
        return false;
    }
    // can access all records
    if ($permission->$action == config('bug.all_permission_access')) {
        return true;
    }

    $get_access_type = data_get($options, 'get_access_type');
    if (!empty($get_access_type)) {
        if ($permission->$action != 0) {
            return true;
        }
    }
    // own records
    if ($modelObj->created_by == $user->id) {
        return true;
    }

    return false;
}

/**
 * redirect with flash message
 * ['url' => "url gives",
 * 'type' => 'danger',
 * 'message' => 'Message.']
 *
 * @author Anil
 */
function redirectWithFlashMsg($params = array()) {
    $params['message'] = $params['message'] ?? null;
    $params['type'] = $params['type'] ?? 'info';

    flash($params['message'], $params['type']);
    return redirect($params['url']);
}


