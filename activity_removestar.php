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
$queryUpdateStarCount = "update activity set starcount = starcount - 1 where id='{$id_activity}'";

$conn->query("SET AUTOCOMMIT=0");
$conn->begin_transaction();

$removeRes = $conn->query($queryRemoveStar);
$updateRes = $conn->query($queryUpdateStarCount);

if(!$removeRes || !$updateRes){
    $resultStatus = "fail";
    $resultData = "取消失败，没有对应收藏信息";
    $conn->rollback();
}else{
    $resultStatus = "success";
    $resultData = "取消收藏成功";
}

$conn->commit();

echo json_encode(array("resultData"=>$resultData, "resultStatus"=>$resultStatus));

mysqli_close($conn);
