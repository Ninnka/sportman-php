<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/2/1
 * Time: 14:25
 */
$name = $_POST["name"];

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$queryActivity = "select * from activity where name like '%{$name}%'";
$resList = $conn->query($queryActivity);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    for($i = 0;$i < mysqli_affected_rows($conn); $i++){
        $resultData[] = mysqli_fetch_assoc($resList);
    }
}else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
