<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >

<head>

  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
    <body>
<?php
ini_set("display_errors", 1);
// Подключаем основной класс
include_once '../class/Class_convert_TableCharacteristics_to_DbCharacteristics.php';
include_once '../class/simple_html_dom.php';
// Создаем класс
$TableCharacteristics = new ClassConvertTable(array("db"=>"tversportru", "host"=>"127.0.0.1", "user"=>"root", "pass"=>"", "jprefix"=>"vlif9"));
$TableCharacteristics->parcerTableToCh();

?>
</body>
</html>