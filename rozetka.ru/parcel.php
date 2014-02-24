<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  
</head>
<style>
    li{
        padding-left: 5px;
    }
</style>
<body>
<?php
// Подключаем класс
include_once '../class/dxlab.rozetka.class.php';
// Создаем класс
$rozetka = new rozetka(array("db"=>"db_rozetka69", "host"=>"127.0.0.1", "user"=>"root", "pass"=>"", "jprefix"=>"fgphv", "folder_images"=>"./data/img/"));
// Проверяем поля
$rozetka->check_db();
// Парсим группы
$rozetka->parseGroup("data/kat.xml");
$rozetka->create_categories();
$rozetka->parselFileXml("data/kat.xml", "data/price.xml");
// тут идет создание картинок
$rozetka->import_images_products(300, 85, "./data/testimages/");
?>
</body>
</html>