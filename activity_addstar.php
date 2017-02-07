<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/2
 * Time: 14:16
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$id_activity = $_POST["id_activity"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryDuplicate = "select id from user_activity_star where id_user='{$id}' and id_activity='{$id_activity}'";
$queryAddStar = "insert into user_activity_star(id_user,id_activity) VALUES('{$id}','{$id_activity}')";
$queryUpdateStarCount = "update activity set starcount = starcount + 1 where id='{$id_activity}'";

$conn->query($queryDuplicate);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "fail";
    $resultData = "您已经收藏过此活动";
    echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
    mysqli_close($conn);
    exit(0);
}

$conn->query("SET AUTOCOMMIT=0");
$conn->begin_transaction();

$insertIndex = $conn->query($queryAddStar);
$updateIndex = $conn->query($queryUpdateStarCount);

if(!$insertIndex || !$updateIndex){
    $resultStatus = "fail";
    $resultData = "发生未知错误，收藏失败";
    $conn->rollback();
}else{
    $resultStatus = "success";
    $resultData = "收藏成功";
}

$conn->commit();

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
