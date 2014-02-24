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

// Выдираем всех производителей
$q = mysql_query("SELECT `manufacturer_id`, `name_ru-RU` FROM `".$jprefix."_jshopping_manufacturers` WHERE `manufacturer_publish` = '1'");
// Проверка на ошибку
if (!$q) {echo mysql_errno($db) . ": " . mysql_error($db);die("Что то не так : Выдираем всех производителей");}

?> <table style='border: 2px solid black;'> <?php
?> <th> Имя(ID) </th> <?php

 while($row=mysql_fetch_object($q)){
 
// Проверка привязан ли хоть один товар к производителю
$prod = mysql_query("SELECT * FROM `".$jprefix."_jshopping_products` WHERE `product_publish` = '1' AND `product_manufacturer_id`='".$row->{'manufacturer_id'}."'");

// Проверка на ошибку
if (!$prod) {echo mysql_errno($db) . ": " . mysql_error($db);die("Что то не так : Проверка привязан ли хоть один товар к производителю");}

print "<tr>";
    if (mysql_num_rows($prod)){
        print "<td style='border: 2px solid black;background-color:green;'>".$row->{'name_ru-RU'}." (".$row->{'manufacturer_id'}.")"."</td>";
    }
     else {
        print "<td style='border: 2px solid black;background-color:red;'>".$row->{'name_ru-RU'}." (".$row->{'manufacturer_id'}.")"."</td>";
     }
print "</tr>";
}

?> </table> <?php

}
?>
</body>
</html>