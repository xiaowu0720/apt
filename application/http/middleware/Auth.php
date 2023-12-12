<?php


namespace app\http\middleware;


use app\common\model\Jwt_base;
use app\http\model\InterfaceAuth;
use think\Request;

class Auth
{
    private $jwtAuth;

    private $redis;

    private $interfaceAuth;

    public function __construct()
    {
        $this->redis = init_redis();
        $this->jwtAuth = new Jwt_base();
        $this->interfaceAuth = new InterfaceAuth();
    }

    public function handle(Request $request, \Closure $next)
    {
        $path = $request->path();
        $method = $request->method();
        if ($path == 'site' && $method == 'GET') {
            return $next($request);
        }
        //获取header里面的token
        $token = explode(" ",$_SERVER['HTTP_AUTHORIZATION'])[1];
        //校验与redis存储的是否一致
        $data = $this->jwtAuth->decode($token);
        $data = object_array($data["data"]);
        //校验解析token之后的格式
        if(!is_array($data))
        {
            echoJson(403,'token错误');
        }
//        $this->check($token, $data);
        if(!$this->interfaceAuth->check($data['roleId'])){
            echoJson(0,"权限不足");
        }
        return $next($request);
    }

    public function check($token, $data)
    {
        if($token != $this->redis->hGet('valid',$data["id"]))
        {
            echoJson(-1,'账号在其他地方登录，请重新登录');
        }
    }
}