<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/5/15
 * Time: 13:42
 */

include "access_allow_origin.php";

$id = $_POST["id"];

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = [];
$resultStatus = "";

$socialList = [];

$querySocialcircle = "select user_socialcircle.* from user_socialcircle where user_socialcircle.id_user='{$id}'";
$querySocialcircleImages = "select id AS id_image,id_socialcircle,imgsrc from user_socialimage where ";

$socialcircleArr = $conn->query($querySocialcircle);
if(mysqli_affected_rows($conn) > 0) {
    $resultStatus = "success";
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $socialList[] = mysqli_fetch_assoc($socialcircleArr);
    }
}else {
    $resultStatus = "fail";
    $resultData = "";
}

$ids = [];
if(count($socialList) > 0) {
    for($j = 0; $j < count($socialList); $j++) {
        $ids[] = $socialList[$j]["id"];
    }
}

$imagesList = [];
if(count($ids) > 0) {
    for($k = 0; $k < count($ids); $k++) {
        if($k != count($ids) - 1) {
            $querySocialcircleImages = $querySocialcircleImages."id_socialcircle=".$ids[$k]." or ";
        }else {
            $querySocialcircleImages = $querySocialcircleImages."id_socialcircle=".$ids[$k];
        }
    }
    $imagesArr = $conn->query($querySocialcircleImages);
    if(mysqli_affected_rows($conn) > 0) {
        for($q = 0; $q < mysqli_affected_rows($conn); $q++) {
            $imagesList[] = mysqli_fetch_assoc($imagesArr);
        }
    }
}

for($o = 0; $o < count($socialList); $o++) {
    $socialList[$o]["images"] = [];
    if(count($imagesList) > 0) {
        for($l = 0; $l < count($imagesList); $l++){
            if($socialList[$o]["id"] == $imagesList[$l]["id_socialcircle"]) {
                $socialList[$o]["images"][] = $imagesList[$l];
            }
        }
    }else {
        $socialList[$o]["images"] = [];
    }
}

$resultData["socialList"] = $socialList;

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);