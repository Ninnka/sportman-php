<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/24
 * Time: 10:14
 */
include "access_allow_origin.php";

$area=$_POST["area"];
$sport_type=$_POST["sport_type"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultStatus = "";
$stadiumArr = [];
$queryStadium = "select * from stadium order by id DESC";
$stadiumList = $conn->query($queryStadium);

if (mysqli_affected_rows($conn) > 0) {
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $stadiumArr[] = mysqli_fetch_assoc($stadiumList);
    }
    $resultStatus = "success";
} else {
    $resultStatus = "fail";
}

echo json_encode(array("resultData" => $stadiumArr, "resultStatus" => $resultStatus));

mysqli_close($conn);
