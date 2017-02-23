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

$queryPayment = "select stadium.*,user_payment_stadium.id AS id_payment,user_payment_stadium.quantity,stadium_tradedetail.name AS tradename from stadium,user_payment_stadium,stadium_tradedetail where stadium.id = user_payment_stadium.id_stadium and user_payment_stadium.id = '{$id}' and user_payment_stadium.id_trade = stadium_tradedetail.id";

$res = $conn->query($queryPayment);
if (mysqli_affected_rows($conn) > 0) {
    $resultData = mysqli_fetch_assoc($res);
    $resultStatus = "success";
}else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);
