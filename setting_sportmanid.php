<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/26
 * Time: 10:09
 */
$id = $_POST["id"];
$sportmanid= $_POST["sportmanid"];
$conn = mysqli_connect("localhost","root","","sportman");
$conn->query("set names uft8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET CHARACTER_SET_RESULTS='UTF8'");

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