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

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryRemoveRecommend = "delete from user_stadium_recommend where id_user='{$id}' and id_stadium='{$id_stadium}'";
$queryUpdateRecommendCount = "update stadium set recommendcount = recommendcount - 1 where id='{$id_stadium}'";

$conn->query("SET AUTOCOMMIT=0");
$conn->begin_transaction();

$removeRes = $conn->query($queryRemoveRecommend);
$updateRes = $conn->query($queryUpdateRecommendCount);

if(!$removeRes || !$updateRes){
    $resultStatus = "fail";
    $resultData = "取消失败，没有对应推荐信息";
    $conn->rollback();
}else{
    $resultStatus = "success";
    $resultData = "取消推荐成功";
}

$conn->commit();

echo json_encode(array("resultData"=>$resultData, "resultStatus"=>$resultStatus));

mysqli_close($conn);
