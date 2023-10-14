<?php


namespace app\common\model;


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Jwt_base
{

    const ERROR_INFO = '令牌无效，请重新登录';

    public $jwt_key;

    public $jwt_valid_time;

    protected $token;

    public function __construct()
    {
        $this->jwt_key='environmental_monitoring';
        $this->jwt_valid_time=3600*72;
        $this->token=[
            'iss' => 'cdcet',//签发者
            'aud' => 'everone',//Jwt所面向的用户
            'iat' => time(),//签发时间
            'nbf' => time(),//在签发什么时间之后才能使用
            'exp' => time()+$this->jwt_valid_time,//过期时间
            'data'=> []
        ];
    }

    //JWT加密
    public function encode($id, $roleId, $username)
    {
        $this->token["data"]["id"] = $id;
        $this->token["data"]["roleId"] = $roleId;
        $jwt = JWT::encode($this->token,$this->jwt_key,'HS256');
        $redis = init_redis();
        $redis->hSet("valid",$id,$jwt);
        $arr = [
            'id'       => $id,
            'roleId'   => $roleId,
            'token'    => $jwt,
            'username' => $username,
        ];
        return $arr;
    }

    //解密
    public function decode($jwt)
    {
        try{
            $decode=JWT::decode($jwt,new key($this->jwt_key,'HS256'));
            $arr=(array)$decode;
        }
        catch (\Exception $e){
            echoJson(1,self::ERROR_INFO);
        }
        return $arr;
    }

    public function check()
    {
        $token=explode(" ",$_SERVER['HTTP_AUTHORIZATION'])[1];
        if($token){
            if(!is_array($this->decode($token)))
            {
                echoJson(0,$this->decode($token));
            }
            return object_array($this->decode($token)['data']);
        }
    }
}