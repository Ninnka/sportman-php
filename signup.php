<?php
/**
 * Created by PhpStorm.
 * User: rainnka
 * Date: 2017/1/22
 * Time: 16:51
 */
$name = $_POST["username"];
$password = $_POST["password"];
//获取时间戳
list($t1, $t2) = explode(' ', microtime());
$timestamp = $t2 . ceil( ($t1 * 1000) );
$data = null;
$resultStatus = null;
$conn = mysqli_connect("localhost","root","","sportman");
$conn->query("set names uft8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET CHARACTER_SET_RESULTS='UTF8'");
$queryUser = "select * from user where name='{$name}'";
$addUser = "insert into user(name,password,timestamp) values('{$name}','{$password}','{$timestamp}')";
$queryRes = $conn->query($queryUser);
if(mysqli_affected_rows($conn) == 0){
    $addRes = $conn->query($addUser);
    if(mysqli_affected_rows($conn) > 0){
        $data="注册成功";
        $resultStatus = "success";
    }else {
        $data="注册失败，发生未知的错误";
        $resultStatus = "fail";
    }
}else {
    $data = "注册失败，用户名已存在";
    $resultStatus = "fail";
}
echo json_encode(array("resultData"=>$data,"resultStatus"=>$resultStatus));