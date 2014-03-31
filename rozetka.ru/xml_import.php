<?php
//set_time_limit(0);
ini_set("display_errors", 1); 
error_reporting(E_ALL);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <style>
    li {
        padding-left: 5px;
    }
</style>
</head>

<body>
<?php
// Подключаем класс
include_once 'helper.php';
// Создаем класс
$rozetka = new rozetka(array("db"=>"db_rozetka69-new", "host"=>"127.0.0.1", "user"=>"root", "pass"=>"", "jprefix"=>"fgphv", "folder_images"=>"./data/foto/"));
// Проверяем поля
$rozetka->checkDb();
// Парсим группы
$rozetka->parseGroup("data/kat.xml");
$rozetka->createCategories();
$rozetka->parseXmlFile("data/kat.xml", "data/price.xml");
// тут идет создание картинок
$rozetka->importImagesProducts(500, 90, "./data/testimages/");
?>
</body>

</html>