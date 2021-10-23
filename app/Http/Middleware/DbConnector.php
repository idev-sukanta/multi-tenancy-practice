<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class DbConnector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!auth()->check()) return;

        Artisan::call('optimize:clear');

        Config::set('database.connections.tenant.database',auth()->user()->tenant->database);

        Config::set('database.default','tenant');

        return $next($request);
    }
}
