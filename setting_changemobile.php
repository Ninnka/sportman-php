<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/26
 * Time: 12:39
 */
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

$conn = mysqli_connect("localhost","root","","sportman");
$conn->query("set names uft8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET CHARACTER_SET_RESULTS='UTF8'");

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