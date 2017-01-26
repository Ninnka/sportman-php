<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/26
 * Time: 13:34
 */
$id = $_POST["id"];
$email = $_POST["email"];

$conn = mysqli_connect("localhost","root","","sportman");
$conn->query("set names uft8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET CHARACTER_SET_RESULTS='UTF8'");

$queryEmail = "update user set email='{$email}' where id='{$id}'";
$conn->query($queryEmail);
$resultStatus= "";
$resultData="";

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    $resultData = $email;
}else{
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));