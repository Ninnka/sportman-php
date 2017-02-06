<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/1
 * Time: 18:58
 */
include "access_allow_origin.php";

$id = $_POST["id"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$query = "select stadium.*,user_payment_stadium.id AS id_payment from stadium,user_payment_stadium where stadium.id = user_payment_stadium.id_stadium and user_payment_stadium.id_user = '{$id}' and user_payment_stadium.status = '待付款'";

$resList = $conn->query($query);
if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    for($i = 0;$i<mysqli_affected_rows($conn);$i++){
        $resultData[] = mysqli_fetch_assoc($resList);
    }
}else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
