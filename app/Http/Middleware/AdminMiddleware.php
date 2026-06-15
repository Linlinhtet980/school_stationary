<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Step 1:Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->withErrors("Please log in first!");
        }

        // Step 2: Check if user is staff
        if (!auth()->user()->isStaff()){
            return redirect()->route("login")->withErrors("You do not have permission to access admin page!");
        }

        // Step 3: Role-based check
        if (!empty($roles)) {
            $userRoledID = auth()->user()->id;

            $roleMapping = [
                "super_admin"=> "1",
                "1" => 1,
                "inventory_manager" => 2,
                "2" => 2,
                "order_staff" => 3,
                "3" => 3,
                "customer_support" => 4,
                "4" => 4,
                "finane_manager" => 5,
                "5" => 5,
            ];

            $allowedRolesIds = [];
            foreach ($roles as $role) {
                $role = strtolower(trim($role));
                if (isset($roleMapping[$role])){
                    $allowedRolesIds[] = $roleMapping[$role];
                }
            }

            // Step4: Check access permission
            if (!in_array($userRoledID,$allowedRolesIds)) {
                abort(403,'Unauthorized. You do not have permission to access this page!');
            }
        }

        return $next($request);
    }
}
