<?php


namespace app\data\controller;


use app\data\model\Datam;
use think\App;
use think\Controller;
use think\Db;
use think\Request;

class Monthly extends Controller
{
    public $datam;

    public function __construct(App $app = null)
    {
        $this->datam = new Datam();
        parent::__construct($app);
    }

    public function data(Request $request)
    {
//        $province = $request->param('province');
//        $county = $request->param('county');
        $action = $request->param('action', 'now');
        $manner = $request->param('manner', 'aqi');
        if ($manner == null) {
            $manner = 'aqi';
        }

        echoJson(1, '查询成功', $this->datam->ranking($action, $manner));
    }
}