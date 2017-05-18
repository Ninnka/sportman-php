<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/5/17
 * Time: 21:52
 */
include "access_allow_origin.php";
include 'connect_mysql.php';
include 'UTF8.php';

$id_user = $_POST["id_user"];
$setType = $_POST["type"];
$f = $_FILES["imgs"];
$name = $f["name"];
$type = $f["type"];
$tmp_name = $f["tmp_name"];
$error = $f["error"];
$size = $f["size"];

$s = new SaeStorage();

require 'toolkit.php';
require_once 'php-sdk-7.1.3/autoload.php';

// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;


// 需要填写你的 Access Key 和 Secret Key
$accessKey = 'tQnxz8zdK-CZiorCQnS-6heq2oupw1RbmNd1wquY';
$secretKey = '_Qv8QbYYPCIVpQmYKnfTS-S8N4uYVhjTONQQBM5F';

// 构建鉴权对象
$auth = new Auth($accessKey, $secretKey);

// 要上传的空间
$bucket = 'sportman';

// 生成上传 Token
$token = $auth->uploadToken($bucket);

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil(($t1 * 1000));

$resultData = [];
$resultStatus = "";
$resultData["ret"] = [];
$resultData["err"] = [];

for($i = 0; $i < count($name); $i++) {
    if ($name[$i]) {
        $tmpFileName = $timestamp . randString() . $name[$i];
        $index = $i;

        // 要上传文件的本地路径
        $filePath = "img/" . $tmpFileName;

        // 上传到七牛后保存的文件名
        $key = $tmpFileName;

        $uploadRes = $s->upload( "img" , $key , $tmp_name[$index]);
        if($uploadRes) {
            $tmpArr = [];
            $tmpArr["key"] = $key;
            $resultData["ret"][] = $tmpArr;
            if($i == count($name) - 1) {
                $conn->query("SET AUTOCOMMIT=0");
                $conn->begin_transaction();
                $querySetImg = "";
                $imgsrc = "http://sportman-img.stor.sinaapp.com/".$resultData["ret"][0]["key"];
                if($setType == "avatar") {
                    $querySetImg = "update user set avatar='{$imgsrc}'";
                }else {
                    $querySetImg = "update user set socialbg='{$imgsrc}'";
                }

                $setImgRes = $conn->query($querySetImg);
                if(mysqli_affected_rows($conn) == 0) {
                    $resultStatus = "fail";
                    $conn->rollback();
                    echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
                    exit(0);
                }
                $conn->commit();
                $resultStatus = "success";
                echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
                exit(0);
            }
        }else {
            if($i == count($name) - 1) {
                $resultStatus = "fail";
                $resultData = "图片上传失败";
                echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
                exit(0);
            }
        }
    }
}

//for($i = 0; $i < count($name); $i++) {
//    if ($name[$i]) {
//        $tmpFileName = $timestamp . randString() . $name[$i];
//        $index = $i;
//        if (move_uploaded_file($tmp_name[$index], "img/" . $tmpFileName)) {
//            // 要上传文件的本地路径
//            $filePath = "img/" . $tmpFileName;
//
//            // 上传到七牛后保存的文件名
//            $key = $tmpFileName;
//
//            // 初始化 UploadManager 对象并进行文件的上传
//            $uploadMgr = new UploadManager();
//
//            // 调用 UploadManager 的 putFile 方法进行文件的上传
//            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//            if ($err !== null) {
//                // 将信息放入需要返回的数据中
//                $resultData["err"][] = $err;
//                $resultStatus = "fail";
//                echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
//
//                // 删除服务器上的临时文件
//                if(is_file("img/".$tmpFileName)){
//                    unlink("img/".$tmpFileName);
//                }
//                exit(0);
//            } else {
//                // 将信息放入需要返回的数据中
//                $resultData["ret"][] = $ret;
//
//                // 开启事务
//                $conn->query("SET AUTOCOMMIT=0");
//                $conn->begin_transaction();
//
//
//
//                // 结束事务
//                $conn->commit();
//
//                $resultStatus = "success";
//                echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
//
//                // 删除服务器上的临时文件
//                if(is_file("img/".$tmpFileName)){
//                    unlink("img/".$tmpFileName);
//                }
//                exit(0);
//            }
//        }
//    }
//}
