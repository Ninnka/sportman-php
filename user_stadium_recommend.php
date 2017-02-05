<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/29
 * Time: 13:19
 */
include "access_allow_origin.php";

$id = $_POST["id"];

$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$queryStadium = "select stadium.* from stadium,user_stadium_recommend where user_stadium_recommend.id_user = '{$id}' and stadium.id = user_stadium_recommend.id_stadium";
$resultStatus = "";
$resultData = [];

$stadiumList = $conn->query($queryStadium);
if (mysqli_affected_rows($conn) > 0) {
    $resultStatus = "success";
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $resultData[] = mysqli_fetch_assoc($stadiumList);
    }
} else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData"=>$resultData,"resultStatus"=>$resultStatus));

mysqli_close($conn);
