<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/1
 * Time: 14:25
 */
include "access_allow_origin.php";

$name = $_GET["name"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$queryStadium = "select * from stadium where name like '%{$name}%'";
$resList = $conn->query($queryStadium);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    for($i = 0;$i < mysqli_affected_rows($conn); $i++){
        $resultData[] = mysqli_fetch_assoc($resList);
    }
}else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
