<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/5/15
 * Time: 15:01
 */

include "access_allow_origin.php";

include 'connect_mysql.php';
include 'UTF8.php';

list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil( ($t1 * 1000) );

$resultData = [];
$resultStatus = "";

$queryActivityHot = "select * from activity where starttime>'{$timestamp}' order by starcount desc";

$activityHotRes = $conn->query($queryActivityHot);
$length = mysqli_affected_rows($conn);

if($length > 0) {
    $resultStatus = "success";
}else {
    
}
if($length > 0 && $length > 10) {
    $length = 10;
}    
for($i = 0; $i < $length; $i++) {
    $resultData[] = mysqli_fetch_assoc($activityHotRes);
}

echo json_encode(array("resultData" => $resultData, "resultStatus" => $resultStatus));

mysqli_close($conn);


