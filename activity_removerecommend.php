<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/3
 * Time: 14:02
 */
$id = $_POST["id"];
$id_activity = $_POST["id_activity"];

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryRemoveRecommend = "delete from user_activity_recommend where id_user='{$id}' and id_activity='{$id_activity}'";

$res = $conn->query($queryRemoveRecommend);
if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    $resultData = "取消推荐成功";
}else {
    $resultStatus = "fail";
    $resultData = "取消失败，没有对应推荐信息";
}

echo json_encode(array("resultData"=>$resultData, "resultStatus"=>$resultStatus));