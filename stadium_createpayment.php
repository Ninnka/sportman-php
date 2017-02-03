<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/2
 * Time: 15:05
 */
$id = $_POST["id"];
$id_stadium = $_POST["id_stadium"];

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil(($t1 * 1000));

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryDuplicate = "select * from user_payment_stadium where id_user='{$id}' and id_stadium='{$id_stadium}'";

$queryAddPayment = "insert into user_payment_stadium(id_user,id_stadium,status,timestamp) VALUES('{$id}','{$id_stadium}','待付款','{$timestamp}')";

$conn->query($queryDuplicate);
if (mysqli_affected_rows($conn) > 0) {
    $resultStatus = "fail";
    $resultData = "您已经申请了此场馆";
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$conn->query("SET AUTOCOMMIT=0");
$conn->begin_transaction();

if(!$conn->query($queryAddPayment)){
    $resultStatus = "fail";
    $resultData = "非常抱歉，新建订单失败";
    $conn->rollback();
}else{
    $resultStatus = "success";
    $resultData = "预定成功";
}

$conn->commit();

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));




