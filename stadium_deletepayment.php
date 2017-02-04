<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/3
 * Time: 14:21
 */

/*
 * 1.获取订单相关信息，订单id，订单设备数量，订单总价
 * 2.删除相关订单，设备数量回升，如果已经付款则退款，并设置订单为已取消
 */

$id_payment = $_POST["id_payment"];
$id_equipment = $_POST["id_equipment"];
$quantity = $_POST["quantity"];
$totalprice = $_POST["totalprice"];

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryCheckStatus = "select status from user_payment_stadium where id='{$id_payment}'";
$queryDeletePayment = "update user_payment_stadium set status='已取消' where id='{$id_payment}'";
$queryIncremain = "update stadium_equipment set remain=remain+$quantity where id='{$id_equipment}'";

$resStatus = $conn->query($queryCheckStatus);
$status = mysqli_fetch_array($resStatus);
if($status["status"] == "已取消"){
    $resultStatus = "fail";
    $resultData = "订单已删除，请勿重复删除";
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$conn->query("SET AUTOCOMMIT=0");
$conn->begin_transaction();

$del = $conn->query($queryDeletePayment);
$inc = $conn->query($queryIncremain);

if (!$del || !$inc) {
    $resultStatus = "fail";
    $resultData = "非常抱歉，删除订单失败";
    $conn->rollback();
} else {
    $resultStatus = "success";
    $resultData = "删除订单成功";
}

$conn->commit();

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);

