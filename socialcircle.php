<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/3/18
 * Time: 21:23
 */
include "access_allow_origin.php";

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = [];
$resultStatus = "";

$querySocialcircle = "select user_socialcircle.*,user.avatar from user_socialcircle,user where user_socialcircle.id_user=user.id";
$querySocialcircleImages = "select id AS id_image,id_socialcircle,imgsrc from user_socialimage where ";

$socialcircleArr = $conn->query($querySocialcircle);
if(mysqli_affected_rows($conn) > 0) {
    $resultStatus = "success";
    for ($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $resultData[] = mysqli_fetch_assoc($socialcircleArr);
    }
}else {
    $resultStatus = "fail";
    $resultData = "";
}

$ids = [];
if(count($resultData) > 0) {
    for($j = 0; $j < count($resultData); $j++) {
        $ids[] = $resultData[$j]["id"];
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

for($o = 0; $o < count($resultData); $o++) {
    $resultData[$o]["images"] = [];
    if(count($imagesList) > 0) {
        for($l = 0; $l < count($imagesList); $l++){
            if($resultData[$o]["id"] == $imagesList[$l]["id_socialcircle"]) {
                $resultData[$o]["images"][] = $imagesList[$l];
            }
        }
    }
}


//var_dump($imagesArr);
echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);

