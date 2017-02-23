<?php
/**
 * Created by PhpStorm.
 * User: lan
 * Date: 2017/2/23
 * Time: 下午7:43
 */
include "access_allow_origin.php";

$id = $_POST["id"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = [];
$resultStatus = "";

$queryFeature = "select * from stadium_reviewfeature where id_stadium='{$id}'";

$featureList = $conn->query($queryFeature);
if(mysqli_affected_rows($conn) > 0) {
    $resultStatus = "success";
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $resultData[] = mysqli_fetch_assoc($featureList);
    }
}else {
    $resultStatus = "fail";
    $resultData = "";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);