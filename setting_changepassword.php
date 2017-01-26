<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/26
 * Time: 11:10
 */
$id = $_POST["id"];
$password = $_POST["password"];

$conn = mysqli_connect("localhost","root","","sportman");
$conn->query("set names uft8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET CHARACTER_SET_RESULTS='UTF8'");

$queryChangePssword = "update user set password='{$password}'where id='{$id}'";
$conn->query($queryChangePssword);
$resultStatus= "";
$resultData="";

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
}else{
    $resultStatus = "fail";
}
echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));