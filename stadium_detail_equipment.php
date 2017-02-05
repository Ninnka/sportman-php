<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/27
 * Time: 14:32
 */
include "access_allow_origin.php";

$id = $_POST["id"];

$conn = mysqli_connect("localhost","root","","sportman");
include 'UTF8.php';

$resultStatus = "";
$resultData = [];

$queryEquipment = "SELECT stadium_equipment.* FROM stadium_equipment,stadium_tradedetail
where stadium_tradedetail.id='{$id}' and stadium_equipment.id_stadium_tradedetail=stadium_tradedetail.id";
$equipmentList = $conn->query($queryEquipment);
if(mysqli_affected_rows($conn) > 0){
    $resultStatus = "success";
    for($i = 0; $i < mysqli_affected_rows($conn); $i++){
        $resultData[] = mysqli_fetch_assoc($equipmentList);
    }
}else{
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
