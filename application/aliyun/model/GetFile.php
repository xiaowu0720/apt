<?php

namespace app\aliyun\model;

use OSS\Core\OssException;
use OSS\OssClient;
use PhpOffice\PhpSpreadsheet\IOFactory;
use think\facade\Config;

class GetFile
{
    public function getFileFromAli($path){
        $oss = Config::get("oss.aliyun_oss");
        // 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录 https://ram.console.aliyun.com 创建RAM账号。
        $accessKeyId = $oss['Key'];
        $accessKeySecret = $oss['KeySecret'];
        // Endpoint以杭州为例，其它Region请按实际情况填写。
        $endpoint = $oss['Endpoint'];
        $bucket= $oss['bucket'];
        // 文件名称。
        $object = $path;
        //临时文件存储地址
        $tempFile = tempnam(sys_get_temp_dir(), 'oss');


        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            $content = $ossClient->getObject($bucket, $object);

            file_put_contents($tempFile, $content);
            // 将 Excel 数据读取到内存中
            $reader = IOFactory::createReaderForFile($tempFile);
            $spreadsheet = $reader->load($tempFile);
            // 将 Excel 数据保存为 .xlsx 文件
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('ExcelSave\devices.xlsx');

            // 删除临时文件
            unlink($tempFile);
            return 'ExcelSave\devices.xlsx';
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
    }
}