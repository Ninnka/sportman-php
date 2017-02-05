<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/31
 * Time: 13:27
 */
include "access_allow_origin.php";

$f = $_FILES["img"];
require 'toolkit.php';
require_once 'php-sdk-7.1.3/autoload.php';

// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
// tQnxz8zdK-CZiorCQnS-6heq2oupw1RbmNd1wquY
// _Qv8QbYYPCIVpQmYKnfTS-S8N4uYVhjTONQQBM5F
// 需要填写你的 Access Key 和 Secret Key
$accessKey = '';
$secretKey = '';

// 构建鉴权对象
$auth = new Auth($accessKey, $secretKey);

// 要上传的空间
$bucket = 'sportman';

// 生成上传 Token
$token = $auth->uploadToken($bucket);

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil(($t1 * 1000));

if ($f) {
    $tmpFileName = $timestamp . randString() . $f["name"];
    if (move_uploaded_file($f["tmp_name"], "img/" . $tmpFileName)) {
        // 要上传文件的本地路径
        $filePath = "img/" . $tmpFileName;

        // 上传到七牛后保存的文件名
        $key = $tmpFileName;

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            echo json_encode($err);
            if(is_file("img/".$tmpFileName)){
                unlink("img/".$tmpFileName);
            }
        } else {
            echo json_encode($ret);
            if(is_file("img/".$tmpFileName)){
                unlink("img/".$tmpFileName);
            }
        }
    }
} else {
    echo "fail";
}
