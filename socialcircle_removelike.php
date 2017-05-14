<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/5/14
 * Time: 23:08
 */

include "access_allow_origin.php";

$id = $_POST["id"];
$id_socialcircle = $_POST["id_socialcircle"];

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil( ($t1 * 1000) );

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = [];
$resultStatus = "";

$queryRemoveLike = "delete from user_sociallike where user_sociallike.id_user='{$id}' and user_sociallike.id_socialcircle='{$id_socialcircle}'";

$removeLikeRes = $conn->query($queryRemoveLike);

if(mysqli_affected_rows($conn) > 0) {
    $resultStatus = "success";
    $resultData = "操作成功";
}else {
    $resultStatus = "fail";
    $resultData = "操作失败";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
