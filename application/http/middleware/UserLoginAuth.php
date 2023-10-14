<?php


namespace app\http\middleware;


use app\common\model\Jwt_base;
use app\user\validate\UserValidate;
use think\Request;

class UserLoginAuth
{
    public function handle(Request $request, \Closure $next)
    {
        $jwt = new Jwt_base();
        $jwt->check();
        return $next($request);
    }
}