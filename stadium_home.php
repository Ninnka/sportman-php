<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/24
 * Time: 10:14
 */
$conn = mysqli_connect("localhost", "root", "", "sportman");
include 'UTF8.php';

$resultStatus = "";
$stadiumArr = [];
$queryStadium = "select * from stadium";
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
