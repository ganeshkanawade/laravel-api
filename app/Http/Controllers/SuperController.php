<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDomainRequest;
use Input;
use App\Domain;
use DB;
use Config;

class SuperController extends Controller
{
    //
    
    function index()
    {
        return view('domain');
    }
    
    function create(CreateDomainRequest $request)
    {
        $url =  $request->root();
        $url_details = parse_url($url);
        
        $domain = new Domain;
        $domain->subdomain = $request->domain.'.'.$url_details['host'];
        $domain->host = $request->host;
        $domain->database = $request->dbname;
        $domain->username = $request->username;
        $domain->password = $request->password;
        $domain->prefix = $request->prefix;
        $domain->status = '1';
        
        $created = $domain->save();
        
        $result = DB::statement("create database {$request->prefix}{$request->dbname}");
        
        if($created && $result)
        {
            Config::set('database.connections.newdomain.host', $request->host );
            Config::set('database.connections.newdomain.database', $request->dbname );
            Config::set('database.connections.newdomain.username', $request->username );
            Config::set('database.connections.newdomain.password', $request->password );
            Config::set('database.connections.newdomain.prefix', $request->prefix );
            
            $statsu = \Artisan::call('migrate', [
            '--force' => true,
            '--database' => 'newdomain',
            ]);
            
        }
        
        
        
        
        
        return \Redirect::to('super/domain')->with('message', 'Your domain has been created!');;
    }
}
