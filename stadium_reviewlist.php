<?php
/**
 * Created by PhpStorm.
 * User: lan
 * Date: 2017/2/6
 * Time: 下午9:09
 */
include "access_allow_origin.php";

$id_stadium = $_GET["id_stadium"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = [];
$resultStatus = "";

$queryReview = "select * from user_review where id_stadium='{$id_stadium}'";
$reviewList = $conn->query($queryReview);
if (mysqli_affected_rows($conn) > 0) {
    $resultStatus = "success";
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $resultData[] = mysqli_fetch_assoc($reviewList);
    }
}else {
    $resultStatus = "fail";
    $resultData = "";
}

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);