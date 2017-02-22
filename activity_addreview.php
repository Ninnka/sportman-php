<?php
/**
 * Created by PhpStorm.
 * User: lan
 * Date: 2017/2/6
 * Time: 下午8:21
 */
include "access_allow_origin.php";

$id_user = $_POST["id_user"];
$id_activity = $_POST["id_activity"];
$review = $_POST["review"];
$score = $_POST["score"];

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil(($t1 * 1000));

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = "";
$resultStatus = "";

$queryDuplicate = "select id from user_review where id_user='{$id_user}' and id_activity='{$id_activity}'";
$queryAddReview = "insert into user_review(id_user,id_activity,review,score,timestamp) VALUES('{$id_user}','{$id_activity}','{$review}','{$score}','{$timestamp}')";
$queryUpdateReviewCount = "update activity set reviewcount = reviewcount + 1 where id='{$id_activity}'";

$conn->query($queryDuplicate);
if (mysqli_affected_rows($conn) > 0) {
    $resultData = "您已经评论过次活动";
    $resultStatus = "fail";
    echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));
    exit(0);
}

$conn->query("SET AUTOCOMMIT=0");
$conn->begin_transaction();

$insertIndex = $conn->query($queryAddReview);
$updateIndex = $conn->query($queryUpdateReviewCount);

if (!$insertIndex || !$updateIndex) {
    $resultData = "评论失败";
    $resultStatus = "fail";
    $conn->rollback();
} else {
    $resultData = "评论成功";
    $resultStatus = "success";
}

$conn->commit();

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);
