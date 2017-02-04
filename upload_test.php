<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/31
 * Time: 11:00
 */
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

// 要上传文件的本地路径
$filePath = 'img/avatar.png';

// 上传到七牛后保存的文件名
$key = 'my-blog-logo.png';

// 初始化 UploadManager 对象并进行文件的上传
$uploadMgr = new UploadManager();

// 调用 UploadManager 的 putFile 方法进行文件的上传
list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
echo "\n====> putFile result: \n";
if ($err !== null) {
    var_dump($err);
//    echo "err:"."<br>";
//    echo json_encode($err);
} else {
    var_dump($ret);
//    echo "res:"."<br>";
//    echo json_encode($ret);
}