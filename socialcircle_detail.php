<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/5/15
 * Time: 10:41
 */

include "access_allow_origin.php";

$id = $_POST["id"];
$id_socialcircle = $_POST["id_socialcircle"];

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil( ($t1 * 1000) );

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = [];
$resultStatus = "";

$socialDetail = '';
$socialDetailImageList = [];

$querySocialDetail = "select user_socialcircle.* from user_socialcircle where user_socialcircle.id='{$id_socialcircle}'";
$querySocialDetailImagesList = "select user_socialimage.* from user_socialimage where id_socialcircle='{$id_socialcircle}'";
$queryLike = "select id from user_sociallike where id_user='$id' and id_socialcircle='{$id_socialcircle}'";

$socialDetailRes = $conn->query($querySocialDetail);
if(mysqli_affected_rows($conn) > 0) {
    $socialDetail = mysqli_fetch_assoc($socialDetailRes);
    $resultStatus = "success";
}else {
    $resultStatus = "fail";
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}
$resultData["socialDetail"] = $socialDetail;

$socialDetailImagesListRes = $conn->query($querySocialDetailImagesList);
if(mysqli_affected_rows($conn) > 0) {
    for($i = 0; $i < mysqli_affected_rows($conn); $i++) {
        $socialDetailImageList[] = mysqli_fetch_assoc($socialDetailImagesListRes);
    }
}
$resultData["images"] = $socialDetailImageList;

$likeRes = $conn->query($queryLike);
if(mysqli_affected_rows($conn) > 0) {
    $resultData["socialDetail"]["isLike"] = true;
}else {
    $resultData["socialDetail"]["isLike"] = false;
}

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);
