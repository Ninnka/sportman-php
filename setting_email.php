<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/26
 * Time: 13:34
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$email = $_POST["email"];

include 'connect_mysql.php';
include 'UTF8.php';

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

mysqli_close($conn);
