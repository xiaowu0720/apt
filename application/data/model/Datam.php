<?php


namespace app\data\model;


use think\Db;
use think\Model;

class Datam extends Model
{
    public function ranking($action, $manner)
    {
        $manner = strtolower($manner);
        $temp1 = $manner;
        if($manner == 'pm25') {
            $temp1 = 'pm2.5';
        }
        $temp = Db::table('site')
            ->field('name')
            ->select();
        $data = [];
        if ($action == 'now') {
            $month = date('Y_m');
            foreach ($temp as $a) {
                $result = Db::table($month.'site')
                    ->field('site,`'. $temp1.'`')
                    ->where('site', $a['name'])
                    ->order('time', 'desc')
                    ->limit('1')
                    ->select();
                if (empty($result)) {
                    $data[] = [
                        'site'  => $a['name'],
                        'value' => null,
                    ];
                }else {
                    $data[] = [
                        'site'  => $a['name'],
                        'value' => $result[0][$temp1]
                    ];
                }

            }
            usort($data, function($a, $b) {
                return $b['value'] - $a['value'];
            });
            $rank = 1;
            foreach ($data as &$item) {
                $item['rank'] = $rank;
                $rank++;
            }
            return $data;
        } elseif ($action == 'lastmoth') {

        } elseif ($action == 'yesterday') {

        } elseif ($action == 'lastyear') {

        }
    }
}