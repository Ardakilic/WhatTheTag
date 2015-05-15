<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AdminMiddleware {
	
	protected $auth;
	
	public function __construct(Guard $auth) {
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		//We don't need to add a second middleware
		//this one can check whether the requester is a user, too
		if($this->auth->guest()){
			return response('Not authorized1', 403);
		}
		
		if($this->auth->user()->role != 'admin') {
			
			$this->auth->logout();
			
			return response('Not authorized', 403);
		}
		
		return $next($request);
	}

}
