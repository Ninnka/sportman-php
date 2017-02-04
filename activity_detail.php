<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/24
 * Time: 16:23
 */
$id = $_POST["id"];
$id_activity = $_POST["id_activity"];

$conn = mysqli_connect("localhost","root","","sportman");
include 'UTF8.php';

$resultStatus = "";
$resultData = "";

$queryDetail = "select a.*,b.id AS recommended,c.id AS stared
from activity a
LEFT JOIN user_activity_recommend b on a.id = b.id_activity and b.id_user = '{$id}'
LEFT JOIN user_activity_star c on a.id = c.id_activity and c.id_user = '{$id}'
where a.id='{$id_activity}'";
$resArr = $conn->query($queryDetail);
if(mysqli_affected_rows($conn) > 0){
    $resultData = mysqli_fetch_assoc($resArr);
}

echo json_encode(array("resultData"=>$resultData, "resultStatus"=>$resultStatus));

mysqli_close($conn);
