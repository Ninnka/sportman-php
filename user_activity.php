<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/26
 * Time: 14:20
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$status = $_POST["status"];

$conn = mysqli_connect("localhost","root","","sportman");
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$queryActivity= "select activity.*,user_activity.registertime from activity,user_activity where user_activity.id_user = '{$id}' and user_activity.id_activity = activity.id and activity.status='{$status}'";
$activityList= $conn->query($queryActivity);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    for($i=0;$i<mysqli_affected_rows($conn);$i++){
        $resultData[] = mysqli_fetch_assoc($activityList);
    }
}else{
    $resultStatus="fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
