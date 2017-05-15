<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/5/15
 * Time: 16:45
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$gender = $_POST["gender"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus= "";
$resultData="";
$queryGender = "update user set gender='{$gender}' where id='{$id}'";
$conn->query($queryGender);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    $resultData = $gender;
}else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);