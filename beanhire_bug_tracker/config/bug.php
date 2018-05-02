<?php

return [
    'fields_list_excluding_description' => [
        'id','bug_code','project_id','sprint_id','status_id','severity_id',
        'reported_by','assigned_to','title','resolved_on', 'created_by', 'created_at','updated_at'
    ],
    'bug_module_id' => 1,
    'projects_module_id' => 2,
    'all_permission_access' => 1,
    'no_permission_to_access_msg' => 'You don\'t have permission to do this action.',
    'permissions_list' => ['view_access', 'edit_access', 'delete_access']
];
