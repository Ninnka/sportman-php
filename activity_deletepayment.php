<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/3
 * Time: 14:02
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

$queryDeletePayment = "update user_payment_activity set status='已取消' where id_user='{$id}' and id_activity='{$id_activity}' and status!='已取消'";
$queryDescCurrentnumber = "update activity set currentnumber = currentnumber - 1 where id='{$id_activity}'";

$queryGetActivityName = "select activity.name from activity where activity.id = '{$id_activity}'";

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

$theme = mysqli_fetch_array($conn->query($queryGetActivityName))["name"];
$queryAddHistory = "insert into user_history(id_user,action,theme,timestamp) VALUES('{$id}','取消参加活动','{$theme}','{$timestamp}')";
if(!$conn->query($queryAddHistory)){
    $resultStatus = "fail";
    $resultData = "更新用户历史失败，申请失败";
    $conn->rollback();
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$queryDeleteUserActivity = "delete from user_activity where id_user='{$id}' and id_activity='{$id_activity}'";
$queryDeleteUARes = $conn->query($queryDeleteUserActivity);
if(mysqli_affected_rows($conn) == 0) {
    $resultStatus = "fail";
    $resultData = "删除用户相关活动失败";
    $conn->rollback();
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$resultData = "success";
$conn->commit();

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);
