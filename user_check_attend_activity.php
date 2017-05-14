<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/24
 * Time: 16:23
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$id_activity = $_POST["id_activity"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus = "";
$resultData = "";

$queryIsAttend = "select user.id from activity,user,user_payment_activity where user_payment_activity.id_user='{$id}' and user_payment_activity.id_activity='{$id_activity}' and user.id=user_payment_activity.id_user and user_payment_activity.id_activity=activity.id and user_payment_activity.status!='已取消'";

$resIsAttend = $conn->query($queryIsAttend);
if (mysqli_affected_rows($conn) > 0) {
    $resultData = 1;
    $resultStatus = "success";
}else {
    $resultData = 0;
    $resultStatus = "fail";
}

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);
