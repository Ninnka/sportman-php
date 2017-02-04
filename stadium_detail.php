<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/24
 * Time: 16:23
 */
$id = $_POST["id"];

$conn = mysqli_connect("localhost","root","","sportman");
include 'UTF8.php';

$resultStatus = "";
$resultData = "";

$queryDetail = "select * from stadium where id='{$id}'";
$queryDetailTrade = "SELECT stadium_tradedetail.* FROM stadium,stadium_tradedetail
where stadium.id='{$id}' and stadium_tradedetail.id_stadium=stadium.id";
$resArr = $conn->query($queryDetail);
if(mysqli_affected_rows($conn) > 0){
    $resultData = mysqli_fetch_assoc($resArr);
}

$tradeArr = [];
$resTradeList = $conn->query($queryDetailTrade);
if(mysqli_affected_rows($conn) > 0){
   for($i = 0;$i<mysqli_affected_rows($conn);$i++){
       $tradeArr[] = mysqli_fetch_assoc($resTradeList);
   }
}
$resultData["tradedetail"] = $tradeArr;

echo json_encode(array("resultData"=>$resultData, "resultStatus"=>$resultStatus));

mysqli_close($conn);
