<?php
namespace App\Http\Middleware;
use DB;
use Closure;
use Auth;
use Redirect;

class CheckLevel
{
    public function handle($request, Closure $next, $level)
    {
		$user = Auth::user();
		if($user->level<$level)
		{
			return Redirect::to('errors/403');
		}
		return $next($request);
    }
}