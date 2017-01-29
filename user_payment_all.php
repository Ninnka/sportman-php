<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/29
 * Time: 17:18
 */
$id = $_POST["id"];

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$queryActivity = "select activity.*,user_payment.id AS id_payment from activity,user_payment where activity.id = user_payment.id_activity and user_payment.id_user = '{$id}' and user_payment.status = '已付款'";
$queryStadium = "select stadium.*,user_payment.id AS id_payment from stadium,user_payment where stadium.id = user_payment.id_stadium and user_payment.id_user = '{$id}' and user_payment.status = '已付款'";

$activityList = $conn->query($queryActivity);
$activityArr = [];
if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    for($i = 0;$i<mysqli_affected_rows($conn);$i++){
        $activityArr[] = mysqli_fetch_assoc($activityList);
    }
}else {
    $resultStatus = "fail";
}
$resultData["paid_activity"] = $activityArr;

$stadiumList = $conn->query($queryStadium);
$stadiumArr = [];
if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    for($i = 0;$i<mysqli_affected_rows($conn);$i++){
        $stadiumArr[] = mysqli_fetch_assoc($stadiumList);
    }
}else {
    $resultStatus = "fail";
}
$resultData["paid_stadium"] = $stadiumArr;

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
