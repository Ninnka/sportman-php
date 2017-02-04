<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/2
 * Time: 15:05
 */
$id = $_POST["id"];
$id_stadium = $_POST["id_stadium"];
$id_trade = $_POST["id_trade"];
$id_equipment = $_POST["id_equipment"];
$quantity = $_POST["quantity"];
$totalprice = $_POST["totalprice"];
//$bookstarttime = $_POST["bookstarttime"];
//$bookendtime = $_POST["bookendtime"];

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil(($t1 * 1000));

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryDulipate = "";
$queryRemain = "select remain from stadium_equipment where id='{$id_equipment}'";
$queryAddPayment = "insert into user_payment_stadium(id_user,id_stadium,id_trade,id_equipment,quantity,totalprice,status,timestamp) VALUES('{$id}','{$id_stadium}','{$id_trade}','{$id_equipment}','{$quantity}','{$totalprice}','待付款','{$timestamp}')";
$queryDescremain = "update stadium_equipment set remain = remain - $quantity where id='{$id_equipment}'";

$resRemain = $conn->query($queryRemain);
$remain = mysqli_fetch_array($resRemain)["remain"];
if ($remain == 0) {
    $resultStatus = "fail";
    $resultData = "非常抱歉，没有剩余的设备可选择";
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
} else if ($remain < $quantity) {
    $resultStatus = "fail";
    $resultData = "非常抱歉，设备数量不足";
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$conn->query("SET AUTOCOMMIT=0");
$conn->begin_transaction();

$addIndex = $conn->query($queryAddPayment);
$updateIndex = $conn->query($queryDescremain);

if (!$addIndex || !$updateIndex) {
    $resultStatus = "fail";
    $resultData = "非常抱歉，新建订单失败";
    $conn->rollback();
}else{
    $resultStatus = "success";
    $resultData = "预定成功";
}

$conn->commit();

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);




