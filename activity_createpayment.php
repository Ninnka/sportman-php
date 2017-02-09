<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/2
 * Time: 15:05
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$id_activity = $_POST["id_activity"];

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil(($t1 * 1000));

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryDuplicate = "select * from user_payment_activity where id_user='{$id}' and id_activity='{$id_activity}'";
$queryNumber = "select totalnumber,currentnumber from activity where id='{$id_activity}'";
$queryAddPayment = "insert into user_payment_activity(id_user,id_activity,status,timestamp) VALUES('{$id}','{$id_activity}','待付款','{$timestamp}')";
$queryUpdateNumber = "update activity set currentnumber=currentnumber+1 where id='{$id_activity}'";


$conn->query($queryDuplicate);

if (mysqli_affected_rows($conn) > 0) {
    $resultStatus = "fail";
    $resultData = "您已经申请了此活动";
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    mysqli_close($conn);
    exit(0);
}

$tmpList = $conn->query($queryNumber);
$tmpArr = mysqli_fetch_array($tmpList);

if ($tmpArr["totalnumber"] <= $tmpArr["currentnumber"]) {
    $resultStatus = "fail";
    $resultData = "活动人数已满，申请失败";
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    mysqli_close($conn);
    exit(0);
}

$conn->query("SET AUTOCOMMIT=0");
$conn->begin_transaction();
$conn->query($queryAddPayment);
$paymentIndex = mysqli_insert_id($conn);
if (!$paymentIndex) {
    $resultStatus = "fail";
    $resultData = "新建订单错误，申请失败";
    $conn->rollback();
}
if (!$conn->query($queryUpdateNumber)) {
    $resultStatus = "fail";
    $resultData = "更新人数错误，申请失败";
    $conn->rollback();
}

$queryAddUserActivity = "insert into user_activity(id_user,id_activity,id_payment,registertime,status) VALUES('{$id}','{$id_activity}','{$paymentIndex}','{$timestamp}','审核中')";
if (!$conn->query($queryAddUserActivity)) {
    $resultStatus = "fail";
    $resultData = "更新用户活动列表失败，申请失败";
    $conn->rollback();
}

$resultStatus = "success";
$resultData = "申请成功";

$conn->commit();

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);
