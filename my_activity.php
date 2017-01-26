<?php
/**
 * Created by PhpStorm.
 * User: MacyaRen
 * Date: 2017/1/26
 * Time: 14:20
 */
$id = $_POST["id"];
$status = $_POST["status"];

$conn = mysqli_connect("localhost","root","","sportman");
$conn->query("set names uft8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET CHARACTER_SET_RESULTS='UTF8'");

$queryActivity="select * ";