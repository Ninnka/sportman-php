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
$address = $_POST["address"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus= "";
$resultData="";
$queryPersonInfo = "update user set gender='{$gender}', address='{$address}' where id={$id}";
$personInfoResult = $conn->query($queryPersonInfo);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    $resultData = array('gender'=>$gender, 'address'=>$address);
}else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
