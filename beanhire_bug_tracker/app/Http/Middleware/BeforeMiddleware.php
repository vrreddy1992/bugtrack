<?php
namespace App\Http\Middleware;

use Closure;
use Route;

class BeforeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $admin_accessable_urls = [
            'Permissions' => ['view', 'setPermission','getPermissions','addRole'],
            'Users' => ['viewUsers', 'addUser','getUsers','viewUser']
        ];

        $currentRouteDetails = getCurrentRouteControllerAndAction();
        $controller = $currentRouteDetails['controller'] ?? null;
        $action = $currentRouteDetails['action'] ?? null;

        $user = auth()->user();
        if (!empty($user) && !$user->is_admin) {
            if (!empty($admin_accessable_urls[$controller]) && in_array($action, $admin_accessable_urls[$controller])) {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
