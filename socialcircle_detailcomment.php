<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/5/15
 * Time: 11:56
 */

include "access_allow_origin.php";

$id = $_POST["id"];
$id_socialcircle = $_POST["id_socialcircle"];
$pageNum = $_POST["pageNum"];
$pageSize = $_POST["pageSize"];

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil( ($t1 * 1000) );

include 'connect_mysql.php';
include 'UTF8.php';

$resultData = [];
$resultStatus = "";

$socialDetailCommentList = [];

$querySocialDetailCommentList = "select user_socialcomment.*,user.avatar from user_socialcomment,user where id_socialcircle='{$id_socialcircle}' and user.id=user_socialcomment.id_user";

$socialDetailCommentListRes = $conn->query($querySocialDetailCommentList);
$commentListLength = mysqli_affected_rows($conn);
if($commentListLength > 0) {
    for($j = 0; $j < $commentListLength; $j++) {
        $socialDetailCommentList[] = mysqli_fetch_assoc($socialDetailCommentListRes);
    }
    $resultStatus = "success";
}
$resultData["comments"] = $socialDetailCommentList;
$resultData["total"] = $commentListLength;

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);

