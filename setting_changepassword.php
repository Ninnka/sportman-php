<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/26
 * Time: 11:10
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$password = $_POST["password"];

include 'connect_mysql.php';
include 'UTF8.php';

$queryChangePssword = "update user set password='{$password}'where id='{$id}'";
$conn->query($queryChangePssword);
$resultStatus= "";
$resultData="";

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    $resultData = "修改密码成功";
}else{
    $resultStatus = "fail";
    $resultData = "修改密码失败";
}
echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
