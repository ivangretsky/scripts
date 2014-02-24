<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>

<body>
<?php

$jprefix = 'vlif9';
$destdb = 'tversportru2';
$db = mysql_connect("127.0.0.1", "root", "");

if (!$db) {
echo "Не удается подключиться к БД";
}
else {
mysql_query('SET NAMES utf8');
mysql_select_db($destdb);
// Массив какие Категории нам нужны
$id_cat = array(83,278);    
    
    
// Создание запроса на получение списка товаров
$id_data = "";
foreach ($id_cat as $id) $id_data .= "`category_id`="."'".$id."' OR ";
// Обрезаем строку, чтобы небыло запятой в конце
$id_data = substr($id_data, 0, strlen($id_data)-4);

// Если нету id категорий ищем все
if($id_data!="")
    $q = mysql_query("SELECT `product_id` FROM `".$jprefix."_jshopping_products_to_categories` WHERE $id_data");
else
    $q = mysql_query("SELECT `product_id` FROM `".$jprefix."_jshopping_products_to_categories`");
// Проверка на ошибку
if (!$q) {echo mysql_errno($db) . ": " . mysql_error($db);die("Что то не так : Создание запроса на получение списка товаров");}

// Получаем товары с полями
$id_id   = "";
while($row=mysql_fetch_row($q)){
    $id_id   .= "`product_id`="."'".$row[0]."' OR ";
}
$id_id = substr($id_id, 0, strlen($id_id)-4);

$q = mysql_query("SELECT `product_id`, `extra_field_11`, `extra_field_12` FROM `".$jprefix."_jshopping_products` WHERE `product_publish` = '1' AND ($id_id)");
// Проверка на ошибку
if (!$q) {echo mysql_errno($db) . ": " . mysql_error($db);die("Что то не так : Создание запроса на получение списка товаров");}

// Обновляем поля
$id_data = "";
while($row=mysql_fetch_object($q)){
    
    if($row->{'extra_field_11'}!="")
        $row->{'extra_field_11'} = (string)floor($row->{'extra_field_11'}/10);
    if($row->{'extra_field_12'}!="")
        $row->{'extra_field_12'} = (string)floor($row->{'extra_field_12'}/10);
        
    $id_data = "`extra_field_11`='".$row->{'extra_field_11'}."', `extra_field_12`='".$row->{'extra_field_12'}."'";
    
    $up = mysql_query("UPDATE `".$jprefix."_jshopping_products` SET $id_data WHERE `product_id`='".$row->{'product_id'}."'");
    
    if (!$up) {echo mysql_errno($db) . ": " . mysql_error($db);die("Что то не так :  Обновляем поля");}
}
print "Все нормально!";
// Обрезаем строку, чтобы небыло запятой в конце
    
    
}
?>
</body>
</html>