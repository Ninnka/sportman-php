<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/27
 * Time: 15:39
 */
$id = $_POST["id"];
$status = $_POST["status"];

$conn = mysqli_connect("localhost","root","","sportman");
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$queryStadium = "SELECT stadium.*,user_stadium.booktime,user_stadium.status from stadium,user_stadium where user_stadium.id_user='{$id}' and user_stadium.status = '{$status}' and user_stadium.id_stadium = stadium.id";
$stadiumList = $conn->query($queryStadium);

if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    for($i=0;$i<mysqli_affected_rows($conn);$i++){
        $resultData[] = mysqli_fetch_assoc($stadiumList);
    }
}else{
    $resultStatus="fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));
