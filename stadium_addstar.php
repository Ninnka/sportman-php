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

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryDuplicate = "select id from user_stadium_star where id_user='{$id}' and id_stadium='{$id_stadium}'";
$queryAddStar = "insert into user_stadium_star(id_user,id_stadium) VALUES('{$id}','{$id_stadium}')";

$conn->query($queryDuplicate);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "fail";
    $resultData = "您已经收藏过此场馆";
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

mysqli_close($conn);
