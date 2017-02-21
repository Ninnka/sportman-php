<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/24
 * Time: 16:23
 */
include "access_allow_origin.php";

$id = $_POST["id"];
$id_stadium = $_POST['id_stadium'];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus = "";
$resultData = "";

//$queryDetail = "select * from stadium where id='{$id}'";
$queryDetail = "select a.*,b.id AS recommended,c.id AS stared
from stadium a
LEFT JOIN user_stadium_recommend b on a.id = b.id_stadium and b.id_user = '{$id}'
LEFT JOIN user_stadium_star c on a.id = c.id_stadium and c.id_user = '{$id}'
where a.id='{$id_stadium}'";
$queryDetailTrade = "SELECT stadium_tradedetail.* FROM stadium,stadium_tradedetail
where stadium.id='{$id_stadium}' and stadium_tradedetail.id_stadium=stadium.id";

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
