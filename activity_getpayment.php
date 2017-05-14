<?php
/**
 * Created by PhpStorm.
 * User: lan
 * Date: 2017/2/23
 * Time: 下午5:54
 */
include "access_allow_origin.php";

$id = $_POST["id"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryPayment = "select user.name AS uname,user.gender AS ugender,user.address AS uaddress,user.mobile AS umobile,activity.*,user_payment_activity.id AS id_payment,user_payment_activity.timestamp AS id_payment_timestamp,user_payment_activity.uid_payment AS uid_payment from activity,user_payment_activity,user where activity.id = user_payment_activity.id_activity and user_payment_activity.id='{$id}' and user.id=user_payment_activity.id_user";

$res = $conn->query($queryPayment);
if (mysqli_affected_rows($conn) > 0) {
    $resultData = mysqli_fetch_assoc($res);
    $resultStatus = "success";
}else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);
