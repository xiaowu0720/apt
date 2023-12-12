<?php


namespace app\data\model;


use think\Db;
use think\Model;

class Datam extends Model
{
    public function ranking($action, $manner)
    {
        $manner = strtolower($manner);
        if($manner == 'pm2.5') {
            $manner = 'pm25';
        }
        $temp = Db::table('site')
            ->field('name')
            ->select();
        $data = [];
        if ($action == 'now') {
            $month = date('Y_m');
            foreach ($temp as $a) {
                $result = Db::table($month.'site')
                    ->field('site,'. $manner)
                    ->where('site', $a['name'])
                    ->order('time', 'desc')
                    ->limit('1')
                    ->select();
                if (empty($result)) {
                    $data[] = [
                        'site'  => $a['name'],
                        $manner => null,
                    ];
                }else {
                    $data[] = [
                        'site'  => $a['name'],
                        $manner => $result[0][$manner]
                    ];
                }

            }
            usort($data, function($a, $b) use ($manner) {
                return $b[$manner] - $a[$manner];
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