<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/29
 * Time: 14:16
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$status = $_POST["status"];

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$reviewActivityArr = [];
$queryReviewActivity = "select activity.*,user_review.status from activity,user_review where user_review.id_user = '{$id}' and user_review.status = '{$status}' and activity.id = user_review.id_activity";

$reviewActivityList = $conn->query($queryReviewActivity);
if (mysqli_affected_rows($conn) > 0) {
    $resultStatus = "success";
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $reviewActivityArr[] = mysqli_fetch_assoc($reviewActivityList);
    }
} else {
    $resultStatus = "fail";
}
$resultData["review_activity"] = $reviewActivityArr;

$reviewStadiumArr = [];
$queryReviewStadium = "select stadium.*,user_review.status from stadium,user_review where user_review.id_user = '{$id}' and user_review.status = '{$status}' and stadium.id = user_review.id_stadium";
$reviewStadiumList = $conn->query($queryReviewStadium);
if (mysqli_affected_rows($conn) > 0) {
    $resultStatus = "success";
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++){
        $reviewStadiumArr[] = mysqli_fetch_assoc($reviewStadiumList);
    }
} else {
    $resultStatus = "fail";
}
$resultData["review_stadium"] = $reviewStadiumArr;

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);
