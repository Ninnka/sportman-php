<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/5/15
 * Time: 16:45
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$address = $_POST["address"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus= "";
$resultData="";
$queryAddress = "update user set address='{$address}' where id='{$id}'";
$conn->query($queryAddress);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    $resultData = $address;
}else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);