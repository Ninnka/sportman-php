<?php
/**
 * Created by PhpStorm.
 * User: lan
 * Date: 2017/2/22
 * Time: 下午8:53
 */
include "access_allow_origin.php";

$id = $_POST["id"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryPay = "update user_payment_stadium set status='已付款' where id='{$id}'";

$conn->query($queryPay);
if(mysqli_affected_rows($conn) > 0){
    $resultData = "";
    $resultStatus = "success";
}else {
    $resultData = "";
    $resultStatus = "fail";
}

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);