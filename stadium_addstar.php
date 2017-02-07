<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/2
 * Time: 14:23
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$id_stadium = $_POST["id_stadium"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryDuplicate = "select id from user_stadium_star where id_user='{$id}' and id_stadium='{$id_stadium}'";
$queryAddStar = "insert into user_stadium_star(id_user,id_stadium) VALUES('{$id}','{$id_stadium}')";
$queryUpdateStarCount = "update stadium set starcount = starcount + 1 where id='{$id_stadium}'";

$conn->query($queryDuplicate);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "fail";
    $resultData = "您已经收藏过此场馆";
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
