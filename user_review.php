<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/29
 * Time: 14:16
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$type = $_POST["type"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$queryReview = "";
switch($type){
    case "activity":
        $queryReview = "select activity.*,user_review.id_user,user_review.review,user_review.score,user_activity.registertime from activity,user_review,user_activity where user_review.id_user = '{$id}' and user_review.id_activity is not null and activity.id = user_review.id_activity and user_activity.id_user = user_review.id_user and user_activity.id_activity = activity.id";
        break;
    case "stadium":
        $queryReview = "select stadium.*,user_review.id_user,user_review.review,user_review.score from stadium,user_review where user_review.id_user = '{$id}' and user_review.id_stadium is not null and stadium.id = user_review.id_stadium";
        break;
}

$reviewList = $conn->query($queryReview);
if (mysqli_affected_rows($conn) > 0) {
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $resultData[] = mysqli_fetch_assoc($reviewList);
    }
    $resultStatus = "success";
} else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);
