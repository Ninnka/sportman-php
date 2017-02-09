<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/2
 * Time: 15:05
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$id_stadium = $_POST["id_stadium"];
$id_trade = $_POST["id_trade"];
$id_equipment = $_POST["id_equipment"];
$quantity = $_POST["quantity"];
$totalprice = $_POST["totalprice"];
$bookstarttime = $_POST["bookstarttime"];
$bookendtime = $_POST["bookendtime"];

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil(($t1 * 1000));

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryDuplicate = "select count(id) AS using from user_payment_stadium where bookstarttime>='{$bookstarttime}' and bookendtime<='{$bookendtime}'";
$queryTotal = "select total from stadium_equipment where id='{$id_equipment}'";
$queryAddPayment = "insert into user_payment_stadium(id_user,id_stadium,id_trade,id_equipment,quantity,totalprice,bookstarttime,bookendtime,status,timestamp) VALUES('{$id}','{$id_stadium}','{$id_trade}','{$id_equipment}','{$quantity}','{$totalprice}','{$bookstarttime}','{$bookendtime}','待付款','{$timestamp}')";

$duplicateamount = mysqli_fetch_array($conn->query($queryDuplicate))["using"];

$resTotal = $conn->query($queryTotal);
$total = mysqli_fetch_array($resTotal)["total"];
$remain = $total - $duplicateamount;
if ($remain < $quantity) {
    $resultStatus = "fail";
    $resultData = "非常抱歉，当前时段设备数量不足";
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$conn->query("SET AUTOCOMMIT=0");
$conn->begin_transaction();

$addIndex = $conn->query($queryAddPayment);
$paymentIndex = mysqli_insert_id($conn);

$queryAddUserStadium = "insert into user_stadium(id_user,id_stadium,id_payment,booktime,status) VALUES('{$id}','{$id_stadium}','{$paymentIndex}','{$timestamp}','待使用')";
$addUserStadiumIndex = $conn->query($queryAddUserStadium);

if (!$addIndex || !$addUserStadiumIndex) {
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




