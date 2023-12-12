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
        $data = $jwt->check();
        $request->data = $data;
        return $next($request);
    }
}