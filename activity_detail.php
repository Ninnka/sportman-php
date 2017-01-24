<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/24
 * Time: 16:23
 */
$id = $_POST["id"];

$conn = mysqli_connect("localhost","root","","sportman");
$conn->query("set names uft8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET CHARACTER_SET_RESULTS='UTF8'");

$resultStatus = "";
$resultData = "";

$queryDetail = "select * from activity where id='{$id}'";
$resArr = $conn->query($queryDetail);
if(mysqli_affected_rows($conn) > 0){
    $resultData = mysqli_fetch_assoc($resArr);
}

echo json_encode(array("resultData"=>$resultData, "resultStatus"=>$resultStatus));