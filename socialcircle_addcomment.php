<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/5/14
 * Time: 22:37
 */

include "access_allow_origin.php";

$id = $_POST["id"];
$id_socialcircle = $_POST["id_socialcircle"];
$comment = $_POST["comment"];
$locate = $_POST["locate"];

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil( ($t1 * 1000) );

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = [];
$resultStatus = "";

$resultData = [];
$resultStatus = "";

$queryAddComment = "insert into user_socialcomment(id_user,id_socialcircle,timestamp,locate,comment) VALUES('{$id}','{$id_socialcircle}','{$timestamp}','{$locate}','{$comment}')";

$addCommentRes = $conn->query($queryAddComment);

if(mysqli_affected_rows($addCommentRes) > 0) {
    $resultStatus = "success";
    $resultData = "操作成功";
}else {
    $resultStatus = "fail";
    $resultData = "操作失败";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
