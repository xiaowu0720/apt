<?php


namespace app\data\model;


use think\Db;
use think\Model;

class Datam extends Model
{
    public function ranking($province, $county, $date)
    {
        $temp = Db::table('site')
            ->where('province', $province)
            ->where('$county', $county)
            ->select();
        $data = [];
        foreach ($temp as $temp1)
        {
            $temp2 = Db::table('sitedata')
                ->where('site', $temp1['name'])
                ->where('')
                ->select();
        }
    }
}