<?php
/**
 * Created by PhpStorm.
 * User: lan
 * Date: 2017/2/6
 * Time: 下午8:21
 */
include "access_allow_origin.php";

$id_user = $_POST["id_user"];
$id_stadium = $_POST["id_stadium"];
$review = $_POST["review"];
$score = $_POST["score"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";
$queryAddReview = "insert into user_review(id_user,id_stadium,review,score) VALUES('{$id_user}','{$id_stadium}','{$review}','{$score}')";

$index = $conn->query($queryAddReview);
if ($index) {
    $resultData = "评论成功";
    $resultStatus = "success";
} else {
    $resultData = "评论失败";
    $resultStatus = "fail";
}

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);
