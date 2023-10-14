<?php


namespace app\http\model;


class InterfaceAuth
{
    public function check($roleId){
        if($roleId == 2){
            return true;
        }
    }
}