<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
  //privateはここでしか使わない変数
  //auth.phpで作ったものと同一にする
  private const GUARD_USER = 'users';
  private const GUARD_OWNER = 'owners';
  private const GUARD_ADMIN = 'admin';
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @param  string|null  ...$guards
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
   */
  public function handle(Request $request, Closure $next, ...$guards)
  {
    //デフォルトはこれ。書き換え。
    // $guards = empty($guards) ? [null] : $guards;

    //checkは認証、ログインしているか確認する

    // foreach ($guards as $guard) {
    //   if (Auth::guard($guard)->check()) {
    //     return redirect(RouteServiceProvider::HOME);
    //   }
    // }

    // return $next($request);
    if (Auth::guard(self::GUARD_USER)->check() && $request->routeIs(('user.*'))) {
      return redirect(RouteServiceProvider::HOME);
    }

    if (Auth::guard(self::GUARD_OWNER)->check() && $request->routeIs(('owner.*'))) {
      return redirect(RouteServiceProvider::OWNER_HOME);
    }

    if (Auth::guard(self::GUARD_ADMIN)->check() && $request->routeIs(('admin.*'))) {
      return redirect(RouteServiceProvider::ADMIN_HOME);
    }
  }
}
