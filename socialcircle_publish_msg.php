<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/31
 * Time: 13:27
 */
include "access_allow_origin.php";
include 'connect_mysql.php';
include 'UTF8.php';

$id_user = $_POST["id_user"];
$locate = $_POST["locate"];
$text = $_POST["text"];
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

$queryCreateText = "insert into user_socialcircle(id_user,timestamp,locate,publish,likecount,commentcount) VALUES('{$id_user}','{$timestamp}','{$locate}','{$text}',0,0)";
$createTextResult = $conn->query($queryCreateText);
if(!$createTextResult) {
    $resultStatus = "fail";
    $resultData = '插入动态消息文本失败';
    $conn->rollback();
    echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
    exit(0);
}else {
    $resultStatus = "success";
}
$textIndex = mysqli_insert_id($conn);

for($i = 0; $i < count($name); $i++) {
    if ($name[$i]) {
        $tmpFileName = $timestamp . randString() . $name[$i];
        $index = $i;

//        if (move_uploaded_file($tmp_name[$index], "img/" . $tmpFileName)) {

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
                    for($j = 0; $j < count($resultData["ret"]); $j++) {
                        $imgsrc = urldecode($resultData["ret"][$j]["key"]);
                        if($j == 0) {
                            $queryCreateImg = $queryCreateImg." VALUES('{$textIndex}','{$imgsrc}')";
                        }else {
                            $queryCreateImg = $queryCreateImg.", ('{$textIndex}','{$imgsrc}')";
                        }
                    }
                    echo "queryCreateImg ".$queryCreateImg;
                    $createImgRes = $conn->query($queryCreateImg);
                    if(mysqli_affected_rows($conn) == 0) {
                        echo "fail";
                        $resultStatus = "fail";
                        $resultData = '导入图片失败';
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

            // 初始化 UploadManager 对象并进行文件的上传
//            $uploadMgr = new UploadManager();
//
//            // 调用 UploadManager 的 putFile 方法进行文件的上传
//            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//            if ($err !== null) {
//                $resultData["err"][] = $err;
//
//                if($i == count($name) - 1 && (count($resultData["err"]) == count($name) || count($resultData["ret"]) == count($name))) {
//                    echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
//                }
//
////                if(is_file("img/".$tmpFileName)){
////                    unlink("img/".$tmpFileName);
////                }
//            } else {
//                $resultData["ret"][] = $ret;
//
//                if($i == count($name) - 1 && (count($resultData["err"]) == count($name) || count($resultData["ret"]) == count($name))) {
//
//                    $conn->query("SET AUTOCOMMIT=0");
//                    $conn->begin_transaction();
//
//
//                    $textIndex = mysqli_insert_id($conn);
//                    $queryCreateImg = "insert into user_socialimage(id_socialcircle, imgsrc)";
//                    for($j = 0; $j < count($resultData["ret"]); $j++) {
//                        $imgsrc = urldecode($resultData["ret"][$j]["key"]);
//                        if($j == 0) {
//                            $queryCreateImg = $queryCreateImg." VALUES('{$textIndex}','{$imgsrc}')";
//                        }else {
//                            $queryCreateImg = $queryCreateImg.", ('{$textIndex}','{$imgsrc}')";
//                        }
//                    }
//                    $createImgRes = $conn->query($queryCreateImg);
//
//                    $conn->commit();
//
//                    $resultStatus = "success";
//
//                    echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
//                }
//
////                if(is_file("img/".$tmpFileName)){
////                    unlink("img/".$tmpFileName);
////                }
//            }

//        }
    }
}

if(count($name) == 0) {
    $resultData="上传信息成功";
    echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
}

