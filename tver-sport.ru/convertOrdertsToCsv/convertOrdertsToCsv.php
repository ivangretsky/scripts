<?php
set_time_limit(0);
ini_set("display_errors", 1); 
error_reporting(E_ALL);
// Подключаем основной класс
include_once 'convertOrdertsToCsvClass.php';
//print_r($_GET["site"]);
//print $_SERVER['REQUEST_URI'];
//exit();

if(empty($_GET["site"]))     $_GET["site"]     = -1;
if(empty($_GET["supplier"])) $_GET["supplier"] = -1;

// Создаем класс
$convertOrdertsToCsv = new convertOrdertsToCsv(array("db"=>"tversportru", "host"=>"127.0.0.1", "user"=>"root", "pass"=>"", "jprefix"=>"vlif9"));
$convertOrdertsToCsv->conver($_GET["site"], $_GET["supplier"]);
$convertOrdertsToCsv->saveFile("orders.csv");
?>