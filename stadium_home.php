<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/24
 * Time: 10:14
 */
$conn = mysqli_connect("localhost", "root", "", "sportman");
$conn->query("set names uft8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET CHARACTER_SET_RESULTS='UTF8'");

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