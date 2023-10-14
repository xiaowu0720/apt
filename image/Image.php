<?php
namespace app\image\controller;

use think\App;
use think\Controller;
use think\Request;

class Image extends Controller{
    public $info;
    public function __construct(App $app = null)
    {
        $this->info=$_REQUEST;
        parent::__construct($app);
    }

    //公共上传图片接口
    public function loadimage(Request $request)
    {
        $file = $request->file('image');
        if ($file) {
            $filename = $file->getFilename(); // 获取原始文件名
            $basename = basename($filename);
            $extension = pathinfo($basename, PATHINFO_EXTENSION);
            $signature = str_replace('.'.$extension, '', $basename);
            $extension = $file->getExtension(); // 获取文件扩展名
            $path = $file->move('public/images', $signature); // 将文件存储到指定路径并更名
            $data=[
                'imagename'=>"/pm2.5/public/images/".$path->getFilename(),
            ];
            echoJson(1,"图片地址",$data['imagename']);
        } else {
            echoJson(0,"没有上传的图片");
        }
    }

}