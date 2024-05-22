<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : Route("login");
    }



    public function handle($request, Closure $next, ...$guards)
    {   
        $request->headers->set('Accept', 'application/json');
        if($token= $request->cookie("auth_token")){
            
            $request->headers->set('Authorization','bearer'.$token);
        }
        $this->authenticate($request,$guards);
       

        return $next($request);
    }
}
