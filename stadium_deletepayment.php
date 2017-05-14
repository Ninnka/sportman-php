<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/3
 * Time: 14:21
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$id_stadium = $_POST["id_stadium"];
$id_payment = $_POST["id_payment"];
//$id_equipment = $_POST["id_equipment"];
//$quantity = $_POST["quantity"];

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil(($t1 * 1000));

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryCheckStatus = "select status from user_payment_stadium where id='{$id_payment}'";
$queryDeletePayment = "update user_payment_stadium set status='已取消' where id='{$id_payment}'";

$queryGetStadiumName = "select stadium.name from stadium where stadium.id = '{$id_stadium}'";

$resStatus = $conn->query($queryCheckStatus);
$status = mysqli_fetch_array($resStatus);
if ($status["status"] == "已取消") {
    $resultStatus = "fail";
    $resultData = "订单已删除，请勿重复删除";
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$conn->query("SET AUTOCOMMIT=0");
$conn->begin_transaction();

$del = $conn->query($queryDeletePayment);

if (!$del) {
    $resultStatus = "fail";
    $resultData = "非常抱歉，删除订单失败";
    $conn->rollback();
} else {
    $resultStatus = "success";
    $resultData = "删除订单成功";
}

$theme = mysqli_fetch_array($conn->query($queryGetStadiumName))["name"];
$queryAddHistory = "insert into user_history(id_user,action,theme,timestamp) VALUES('{$id}','取消预定场馆','{$theme}','{$timestamp}')";

if (!$conn->query($queryAddHistory)) {
    $resultStatus = "fail";
    $resultData = "更新用户历史失败，预定失败";
    $conn->rollback();
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$queryDeleteUserStadium = "delete from user_stadium where id_user='{$id}' and id_stadium='{$id_stadium}'";
$queryDeleteUSRes = $conn->query($queryDeleteUserStadium);
if(mysqli_affected_rows($conn) == 0) {
    $resultStatus = "fail";
    $resultData = "删除用户相关场馆失败";
    $conn->rollback();
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$conn->commit();

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);

