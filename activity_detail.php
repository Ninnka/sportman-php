<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/24
 * Time: 16:23
 */
include "access_allow_origin.php";
$queryIsAttend = "select user.id from activity,user where user.id=user_payment_activity. and user_payment_activity.id_activity=activity.id";
$id = $_POST["id"];
$id_activity = $_POST["id_activity"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus = "";
$resultData = "";

$queryDetail = "select a.*,b.id AS recommended,c.id AS stared
from activity a
LEFT JOIN user_activity_recommend b on a.id = b.id_activity and b.id_user = '{$id}'
LEFT JOIN user_activity_star c on a.id = c.id_activity and c.id_user = '{$id}'
where a.id='{$id_activity}'";

$queryDetailRegulartion = "select order_id,title,content from activity_regulartion where id_activity='{$id_activity}'";

$resArr = $conn->query($queryDetail);
if (mysqli_affected_rows($conn) > 0) {
    $resultData = mysqli_fetch_assoc($resArr);
}

$resRegulartionList = $conn->query($queryDetailRegulartion);
$resRegulartionArr = [];

if (mysqli_affected_rows($conn) > 0) {
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $resRegulartionArr[] = mysqli_fetch_assoc($resRegulartionList);
    }
}

$resultData["regulartion"] = $resRegulartionArr;

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);
