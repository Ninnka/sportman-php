<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/23
 * Time: 11:45
 */
// 响应get请求
$conn = mysqli_connect("localhost", "root", "", "sportman");
$conn->query("set names uft8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET CHARACTER_SET_RESULTS='UTF8'");

$queryBanner = "select * from activity_banner";
$queryActivity = "select * from activity";
$resultStatus = "";

$activityArr = $conn->query($queryActivity);
$activityList = [];
for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
    $activityList[] = mysqli_fetch_assoc($activityArr);
}

$bannerArr = $conn->query($queryBanner);
$bannerList = [];
for ($j = 0; $j < mysqli_affected_rows($conn); $j++) {
    $bannerList[] = mysqli_fetch_assoc($bannerArr);
}
$resultStatus = "success";

echo json_encode(array("resultData" => array("activityList" => $activityList, "bannerList" => $bannerList), "resultStatus" => $resultStatus));