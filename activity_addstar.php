<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/2
 * Time: 14:16
 */
$id = $_POST["id"];
$id_activity = $_POST["id_activity"];

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryDuplicate = "select id from user_activity_star where id_user='{$id}' and id_activity='{$id_activity}'";
$queryAddStar = "insert into user_activity_star(id_user,id_activity) VALUES('{$id}','{$id_activity}')";

$conn->query($queryDuplicate);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "fail";
    $resultData = "您已经收藏过此活动";
}else {
    $conn->query($queryAddStar);
    if(mysqli_affected_rows($conn) > 0){
        $resultStatus = "success";
        $resultData = "收藏成功";
    }else {
        $resultStatus = "fail";
        $resultData = "发生未知错误，收藏失败";
    }
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));