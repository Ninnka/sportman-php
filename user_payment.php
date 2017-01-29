<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/29
 * Time: 17:12
 */
$id = $_POST["id"];
$type = $_POST["type"];

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$query = "";
switch ($type){
    case "活动":
        $query = "select activity.*,user_payment.id AS id_payment from activity,user_payment where activity.id = user_payment.id_activity and user_payment.id_user = '{$id}' and user_payment.status = '待付款'";
        break;
    case "场馆":
        $query = "select stadium.*,user_payment.id AS id_payment from stadium,user_payment where stadium.id = user_payment.id_stadium and user_payment.id_user = '{$id}' and user_payment.status = '待付款'";
        break;
}
$resList = $conn->query($query);
if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    for($i = 0;$i<mysqli_affected_rows($conn);$i++){
        $resultData[] = mysqli_fetch_assoc($resList);
    }
}else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
