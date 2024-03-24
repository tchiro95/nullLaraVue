<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{

  //RouteServiceProviderでasで名前をつけているのでuser. owner. admin. が使える。
  //asはgroupでひとまとめ、nameは個々のrouteに名前をつける
  protected $user_route = 'user.login';
  protected $owner_route = 'owner.login';
  protected $admin_route = 'admin.login';


  /**
   * Get the path the user should be redirected to when they are not authenticated.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return string|null
   */
  protected function redirectTo($request)
  {
    //デフォルト。認証されてない人はすべてlogin
    // if (!$request->expectsJson()) {
    //   return route('login');
    // }
    //上の変数に伴ってそれぞれのrouteをする。
    if (!$request->expectsJson()) {
      if (Route::is('owner.*')) {
        return route($this->owner_route);
      } else if (Route::is('admin.*')) {
        return route($this->admin_route);
      } else {
        return route($this->user_route);
      }
    }
  }
}
