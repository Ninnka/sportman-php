<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/22
 * Time: 11:12
 */
$name = $_POST["username"];
$password = $_POST["password"];
$conn = mysqli_connect("localhost","root","","sportman");
$conn->query("set names uft8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET CHARACTER_SET_RESULTS='UTF8'");
$queryUser = "select * from user where name='{$name}' and password='{$password}'";
$res = $conn->query($queryUser);
$resArr = [];
$resultStatus = "";
if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    for($i = 0; $i< mysqli_affected_rows($conn);$i++){
        $resArr[] = mysqli_fetch_assoc($res);
    }
}else {
    $resultStatus = "fail";
}
echo json_encode(array("resultData"=>$resArr,"resultStatus"=>$resultStatus));

