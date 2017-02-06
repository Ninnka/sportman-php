<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/2
 * Time: 14:41
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$id_activity = $_POST["id_activity"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryDuplicate = "select id from user_activity_recommend where id_user='{$id}' and id_activity='{$id_activity}'";
$queryAddRecommend = "insert into user_activity_recommend(id_user,id_activity) VALUES('{$id}','{$id_activity}')";

$conn->query($queryDuplicate);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "fail";
    $resultData = "您已经推荐过此活动";
}else {
    $conn->query($queryAddRecommend);
    if(mysqli_affected_rows($conn) > 0){
        $resultStatus = "success";
        $resultData = "推荐成功";
    }else {
        $resultStatus = "fail";
        $resultData = "发生未知错误，推荐失败";
    }
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
