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
$queryFeature = "select * from stadium_reviewfeature where id_stadium='{$id_stadium}'";
$queryTotalScore = "select totalscore from stadium where id = '{$id_stadium}'";

$reviewArr = [];
$reviewList = $conn->query($queryReview);
if (mysqli_affected_rows($conn) > 0) {
    $resultStatus = "success";
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $reviewArr[] = mysqli_fetch_assoc($reviewList);
    }
}else {
    $resultStatus = "fail";
    $resultData = "";
}

$featureArr = [];
$featureList = $conn->query($queryFeature);
if(mysqli_affected_rows($conn) > 0) {
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $featureArr[] = mysqli_fetch_assoc($featureList);
    }
}

$totalscore = mysqli_fetch_array($conn->query($queryTotalScore))["totalscore"];

echo json_encode(array("resultData" => array("totalscore"=>$totalscore,"reviewList"=>$reviewArr,"featureList"=>$featureArr), "resultStatus" => $resultStatus));

mysqli_close($conn);