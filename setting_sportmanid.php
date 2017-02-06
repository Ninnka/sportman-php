<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/26
 * Time: 10:09
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$sportmanid= $_POST["sportmanid"];

include 'connect_mysql.php';
include 'UTF8.php';

$querySetspmid = "update user set sportmanid='{$sportmanid}' where id='{$id}'";
$conn->query($querySetspmid);
$resultStatus= "";
$resultData="";
if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    $resultData=$sportmanid;
}else {
    $resultStatus = "fail";
}
echo json_encode(array("$resultData"=>$resultData,"$resultStatus"=>$resultStatus));

mysqli_close($conn);
