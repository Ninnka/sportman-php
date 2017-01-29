<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/29
 * Time: 13:46
 */
$id = $_POST["id"];

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultStatus = "";
$resultData = [];
$queryActivity = "select activity.* from activity,user_activity_star where user_activity_star.id_user = '{$id}' and activity.id = user_activity_star.id_activity";

$activityList = $conn->query($queryActivity);
if (mysqli_affected_rows($conn) > 0) {
    $resultStatus = "success";
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $resultData[] = mysqli_fetch_assoc($activityList);
    }
} else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));