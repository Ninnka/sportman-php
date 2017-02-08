<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/27
 * Time: 15:39
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$status = $_POST["status"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$queryStadium = "SELECT stadium.position,stadium.post,user_stadium.booktime,user_stadium.status,stadium_equipment.name,user_payment_stadium.quantity from stadium,user_stadium,stadium_equipment,user_payment_stadium where user_stadium.id_user='{$id}' and user_stadium.status = '{$status}'";
if($status != "all"){
    $queryStadium = "SELECT user_stadium.status,stadium.name,stadium.post,stadium.position from stadium,user_stadium where user_stadium.id_user='{$id}' and user_stadium.status = '{$status}' and user_stadium.id_stadium = stadium.id";
}

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

mysqli_close($conn);
