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

$uid_payment = (string)$id.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8).(string)$id_activity;
//var_dump($uid_payment);

$queryDuplicate = "select * from user_payment_activity where id_user='{$id}' and id_activity='{$id_activity}' and status!='已取消'";
$queryNumber = "select totalnumber,currentnumber from activity where id='{$id_activity}'";
$queryAddPayment = "insert into user_payment_activity(id_user,id_activity,status,timestamp,uid_payment) VALUES('{$id}','{$id_activity}','待付款','{$timestamp}','{$uid_payment}')";
$queryUpdateNumber = "update activity set currentnumber=currentnumber+1 where id='{$id_activity}'";

$queryGetActivityName = "select activity.name from activity where activity.id = '{$id_activity}'";

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
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}
if (!$conn->query($queryUpdateNumber)) {
    $resultStatus = "fail";
    $resultData = "更新人数错误，申请失败";
    $conn->rollback();
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$queryAddUserActivity = "insert into user_activity(id_user,id_activity,id_payment,registertime,status) VALUES('{$id}','{$id_activity}','{$paymentIndex}','{$timestamp}','审核中')";
if (!$conn->query($queryAddUserActivity)) {
    $resultStatus = "fail";
    $resultData = "更新用户活动列表失败，申请失败";
    $conn->rollback();
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$theme = mysqli_fetch_array($conn->query($queryGetActivityName))["name"];
$queryAddHistory = "insert into user_history(id_user,action,theme,timestamp) VALUES('{$id}','参加活动','{$theme}','{$timestamp}')";
if(!$conn->query($queryAddHistory)){
    $resultStatus = "fail";
    $resultData = "更新用户历史失败，申请失败";
    $conn->rollback();
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$resultStatus = "success";
$resultData = "申请成功";

$conn->commit();

echo json_encode(array("resultData" => array("id_payment" => $paymentIndex, "id_activity" => $id_activity), "resultStatus" => $resultStatus));

mysqli_close($conn);
