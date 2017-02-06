<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/29
 * Time: 17:12
 */
include "access_allow_origin.php";

$id = $_POST["id"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$query = "select activity.*,user_payment_activity.id AS id_payment from activity,user_payment_activity where activity.id = user_payment_activity.id_activity and user_payment_activity.id_user = '{$id}' and user_payment_activity.status = '待付款'";

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

mysqli_close($conn);
