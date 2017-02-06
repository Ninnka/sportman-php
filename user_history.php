<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/30
 * Time: 15:07
 */
include "access_allow_origin.php";

$id = $_POST["id"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$queryHistory = "select * from user_history where user_history.id_user = '{$id}'";
$historyList = $conn->query($queryHistory);
if (mysqli_affected_rows($conn) > 0) {
    $resultStatus = "success";
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $resultData[] = mysqli_fetch_assoc($historyList);
    }
} else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
