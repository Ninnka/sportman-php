<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/3
 * Time: 14:19
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$id_stadium = $_POST["id_stadium"];

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryRemoveRecommend = "delete from user_stadium_recommend where id_user='{$id}' and id_stadium='{$id_stadium}'";

$res = $conn->query($queryRemoveRecommend);
if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    $resultData = "取消推荐成功";
}else {
    $resultStatus = "fail";
    $resultData = "取消失败，没有对应推荐信息";
}

echo json_encode(array("resultData"=>$resultData, "resultStatus"=>$resultStatus));

mysqli_close($conn);
