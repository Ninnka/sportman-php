<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/3
 * Time: 14:01
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$id_activity = $_POST["id_activity"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryRemoveStar = "delete from user_activity_star where id_user='{$id}' and id_activity='{$id_activity}'";

$res = $conn->query($queryRemoveStar);
if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    $resultData = "取消收藏成功";
}else {
    $resultStatus = "fail";
    $resultData = "取消失败，没有对应收藏信息";
}

echo json_encode(array("resultData"=>$resultData, "resultStatus"=>$resultStatus));

mysqli_close($conn);