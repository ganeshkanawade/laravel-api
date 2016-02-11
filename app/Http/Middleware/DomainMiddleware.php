<?php

namespace App\Http\Middleware;

use Closure;
use App\Domain;
use Request;
use Config;

class DomainMiddleware
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
        
        $subdomain = $request->route()->account;
        $url =  $request->root();
        $url_details = parse_url($url);
        
        $domain = Domain::where( 'subdomain', $url_details['host'] )->where( 'status', 1 )->first();

        if( ! $domain ) {
            abort(404);
        }
        
        Config::set('database.connections.subdomain.host', $domain->host );
        Config::set('database.connections.subdomain.database', $domain->database );
        Config::set('database.connections.subdomain.username', $domain->username );
        Config::set('database.connections.subdomain.password', $domain->password );
        Config::set('database.connections.subdomain.prefix', $domain->prefix );
        
        return $next($request);
    }
}
