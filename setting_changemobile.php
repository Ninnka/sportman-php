<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/26
 * Time: 12:39
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$mobile = "";
$type = $_POST["type"];
switch ($type){
    case "change":
    case "bind":
        $mobile = $_POST["mobile"];
        break;
    case "unbind":
        break;
}

include 'connect_mysql.php';
include 'UTF8.php';

$queryMobile = "update user set mobile='{$mobile}' where id='{$id}'";
$conn->query($queryMobile);
$resultData  = "";
$resultStatus = "";

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    $resultData = $mobile;
}else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"$resultStatus"=>$resultStatus));

mysqli_close($conn);
