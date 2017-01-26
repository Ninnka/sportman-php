<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/26
 * Time: 19:53
 */
$id = $_POST["id"];

$conn = mysqli_connect("localhost","root","","sportman");
include 'UTF8.php';

$queryRecommend = "select activity.* from activity,user_activity_recommend
WHERE user_activity_recommend.id_user = '{$id}' and user_activity_recommend.id_activity = activity.id";
$resList = $conn->query($queryRecommend);
$resultData = [];
$resultStatus = "";
if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    for($i = 0;$i<mysqli_affected_rows($conn);$i++){
        $resultData[] = mysqli_fetch_assoc($resList);
    }
}else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
