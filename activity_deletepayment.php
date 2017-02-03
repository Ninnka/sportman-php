<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/3
 * Time: 14:02
 */
$id = $_POST["id"];
$id_activity = $_POST["id_activity"];

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryDeletePayment = "update user_payment_activity set status='已取消' where id_user='{$id}' and id_activity='{$id_activity}'";
$queryDescCurrentnumber = "update activity set currentnumber = currentnumber - 1 where id='{$id_activity}'";

$conn->query("SET AUTOCOMMIT=0");
$conn->begin_transaction();

if(!$conn->query($queryDeletePayment)){
    $resultStatus = "fail";
    $resultData = "取消订单失败";
}else{
    if(!$conn->query($queryDescCurrentnumber)){
        $resultStatus = "fail";
        $resultData = "取消订单失败";
        $conn->rollback();
    }else{
        $resultStatus = "success";
        $resultData = "取消订单成功";
    }
}
$conn->commit();

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
