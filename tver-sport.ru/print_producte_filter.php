<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >

<head>

  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<?php


function view_table($_array_data, $id_cat){
    
$jprefix = 'vlif9';
$destdb = 'tversportru2';
$db = mysql_connect("127.0.0.1", "root", "");

if (!$db) {
echo "Не удается подключиться к БД";
}

mysql_query('SET NAMES utf8');
mysql_select_db($destdb);
    
// Тут проверяем все характеристики
// Собираем поля да sql запроса
$field_data = "";
foreach ($_array_data as $data)if($data["print"]==true){
   $field_data .= "`".$data["pole"]."`, ";
}
// Обрезаем строку, чтобы небыло запятой в конце
$field_data = substr($field_data, 0, strlen($field_data)-2);
// Если пусто значит выбрать все
$field_data = $field_data=="" ? "*"  : $field_data;



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


// Создание запроса на получение самих характеристик
$id_data = "";
while($row=mysql_fetch_row($q)) $id_data .= "`product_id`="."'".$row[0]."' OR ";
// Проверка если нету товаров
if($id_data=="")die("Нету товаров");
// Обрезаем строку, чтобы небыло запятой в конце
$id_data = substr($id_data, 0, strlen($id_data)-4);
$q = mysql_query("SELECT `product_id`, $field_data FROM `".$jprefix."_jshopping_products` WHERE `product_publish` = '1' AND ($id_data)");
// Проверка на ошибку
if (!$q) {echo mysql_errno($db) . ": " . mysql_error($db);die("Что то не так : Создание запроса на получение самих характеристик ");}



// Выводим в таблицу все
?> <table style='border: 2px solid black;'> <?php

    // Выводим заголовки
    print "<th style='border: 2px solid black;'>№</th>";
    print "<th style='border: 2px solid black;'>ID</th>";
    foreach ($_array_data as $data){
       if($data["print"]==true)print "<th style='border: 2px solid black;'>".$data["name"]."</th>";
    }
    
    // Дополнительная информация
    // Номер поля
    $number_field = 0;
    while($row=mysql_fetch_object($q))
        {
        // Проверка нужен ли этот товар
        $activ = false;
       
        // Проверка имени
        if(isset($row->{'name_ru-RU'}))if($row->{'name_ru-RU'}==""){$activ =true;$row->{'name_ru-RU'}="net";}
        // Проверка Алиас
        if(isset($row->{'alias_ru-RU'}))if($row->{'alias_ru-RU'}==""){$activ =true;$row->{'alias_ru-RU'}="net";}
        // Проверка Цены
        if(isset($row->{'product_price'}))if($row->{'product_price'}==""||$row->{'product_price'}=="0"){$activ =true;$row->{'product_price'}="net";}
        // Проверка extra_field_1
        if(isset($row->{'extra_field_1'}))if($row->{'extra_field_1'}==""){$activ =true;$row->{'extra_field_1'}="net";}
        // Проверка extra_field_2
        if(isset($row->{'extra_field_2'})) if($row->{'extra_field_2'}==""){$activ =true;$row->{'extra_field_2'}="net";}
        // Проверка extra_field_3
        if(isset($row->{'extra_field_3'}))if($row->{'extra_field_3'}==""){$activ =true;$row->{'extra_field_3'}="net";}
        // Проверка extra_field_4
        if(isset($row->{'extra_field_4'}))if($row->{'extra_field_4'}==""){$activ =true;$row->{'extra_field_4'}="net";}
        // Проверка extra_field_5
        if(isset($row->{'extra_field_5'}))if($row->{'extra_field_5'}==""){$activ =true;$row->{'extra_field_5'}="net";}
        // Проверка extra_field_6
        if(isset($row->{'extra_field_6'}))if($row->{'extra_field_6'}==""){$activ =true;$row->{'extra_field_6'}="net";}
        // Проверка extra_field_7
        if(isset($row->{'extra_field_7'}))if($row->{'extra_field_7'}==""){$activ =true;$row->{'extra_field_7'}="net";}
        // Проверка extra_field_8
        if(isset($row->{'extra_field_8'}))if($row->{'extra_field_8'}==""){$activ =true;$row->{'extra_field_8'}="net";}
        // Проверка extra_field_9
        if(isset($row->{'extra_field_9'}))if($row->{'extra_field_9'}==""){$activ =true;$row->{'extra_field_9'}="net";}
        // Проверка extra_field_10
        if(isset($row->{'extra_field_10'}))if($row->{'extra_field_10'}==""){$activ =true;$row->{'extra_field_10'}="net";}
        // Проверка extra_field_11
        if(isset($row->{'extra_field_11'}))if($row->{'extra_field_11'}==""){$activ =true;$row->{'extra_field_11'}="net";}
        // Проверка extra_field_12
        if(isset($row->{'extra_field_12'}))if($row->{'extra_field_12'}==""){$activ =true;$row->{'extra_field_12'}="net";}
        // Проверка extra_field_13
        if(isset($row->{'extra_field_13'}))if($row->{'extra_field_13'}==""){$activ =true;$row->{'extra_field_13'}="net";}
        // Проверка product_manufacturer_id
        if(isset($row->{'product_manufacturer_id'}))if($row->{'product_manufacturer_id'}==""||$row->{'product_manufacturer_id'}=="0"){$activ =true;$row->{'product_manufacturer_id'}="net";}
        
        // Если у товара все поля которые были вкл в масссиве нормальные то идем дальше по массиву
        if(!$activ) continue;
        $number_field++;
        // Выводим сами характеристики
        print "<tr>";
        print "<td style='text-align:center;border: 2px solid black;'>".$number_field."</td>";
          foreach($row as $r) {
             if($r!="net") 
                 print "<td style='text-align:center;border: 2px solid black;'>".$r."</td>";
             else 
                 print "<td style='text-align:center;border: 2px solid black;background-color:red;'>".$r."</td>";
          }
        print "</tr>";
    }
    
    // Выводим значения

?> </table> <?php
};




// Массив данных
$_array_data = array(
    array("pole" => "name_ru-RU",     "name" => "Имя",                                 "print" =>  true),
    array("pole" => "alias_ru-RU",    "name" => "Алиас",                               "print" =>  true),
    array("pole" => "product_price",  "name" => "Цена",                                "print" =>  true),
    array("pole" => "extra_field_1",  "name" => "Максимальный вес пользователя, кг",   "print" => false),
    array("pole" => "extra_field_2",  "name" => "Тип велотренажера",                   "print" => false),
    array("pole" => "extra_field_3",  "name" => "Система нагружения",                  "print" => false),
    array("pole" => "extra_field_4",  "name" => "Вес маховика, кг",                    "print" => false),
    array("pole" => "extra_field_5",  "name" => "Вес брутто, кг",                      "print" =>  true),
    array("pole" => "extra_field_6",  "name" => "Длина упаковки, мм",                  "print" =>  true),
    array("pole" => "extra_field_7",  "name" => "Ширина упаковки, мм",                 "print" =>  true),
    array("pole" => "extra_field_8",  "name" => "Высота упаковки,мм",                  "print" =>  true),
    array("pole" => "extra_field_9",  "name" => "Максимальный вес пользователя, кг",   "print" =>  true),
    array("pole" => "extra_field_10", "name" => "Тип беговой дорожки",                 "print" =>  true),
    array("pole" => "extra_field_11", "name" => "Длина бегового полотна, мм",          "print" =>  true),
    array("pole" => "extra_field_12", "name" => "Ширина бегового полотна, мм",         "print" =>  true),
    array("pole" => "extra_field_13", "name" => "Максимальная скорость, км/ч",         "print" =>  true),
    array("pole" => "product_manufacturer_id",  "name" => "Производитель",             "print" =>  true)
);

    


// Массив какие Категории нам нужны
print "<h1>Вывод беговые дорожки</h1>";
view_table($_array_data, array(83,278));








// Массив данных
$_array_data = array(
    array("pole" => "name_ru-RU",     "name" => "Имя",                                 "print" =>  true),
    array("pole" => "alias_ru-RU",    "name" => "Алиас",                               "print" =>  true),
    array("pole" => "product_price",  "name" => "Цена",                                "print" =>  true),
    array("pole" => "extra_field_1",  "name" => "Максимальный вес пользователя, кг",   "print" =>  true),
    array("pole" => "extra_field_2",  "name" => "Тип велотренажера",                   "print" =>  true),
    array("pole" => "extra_field_3",  "name" => "Система нагружения",                  "print" =>  true),
    array("pole" => "extra_field_4",  "name" => "Вес маховика, кг",                    "print" =>  true),
    array("pole" => "extra_field_5",  "name" => "Вес брутто, кг",                      "print" =>  true),
    array("pole" => "extra_field_6",  "name" => "Длина упаковки, мм",                  "print" =>  true),
    array("pole" => "extra_field_7",  "name" => "Ширина упаковки, мм",                 "print" =>  true),
    array("pole" => "extra_field_8",  "name" => "Высота упаковки,мм",                  "print" =>  true),
    array("pole" => "extra_field_9",  "name" => "Максимальный вес пользователя, кг",   "print" =>  false),
    array("pole" => "extra_field_10", "name" => "Тип беговой дорожки",                 "print" =>  false),
    array("pole" => "extra_field_11", "name" => "Длина бегового полотна, мм",          "print" =>  false),
    array("pole" => "extra_field_12", "name" => "Ширина бегового полотна, мм",         "print" =>  false),
    array("pole" => "extra_field_13", "name" => "Максимальная скорость, км/ч",         "print" =>  false),
    array("pole" => "product_manufacturer_id",  "name" => "Производитель",             "print" =>  true)
);
// Массив какие Категории нам нужны
print "<h1>Велотренажеры</h1>";
view_table($_array_data, array(84,312));




// Массив данных
$_array_data = array(
    array("pole" => "name_ru-RU",     "name" => "Имя",                                 "print" =>  true),
    array("pole" => "alias_ru-RU",    "name" => "Алиас",                               "print" =>  true),
    array("pole" => "product_price",  "name" => "Цена",                                "print" =>  true),
    array("pole" => "extra_field_1",  "name" => "Максимальный вес пользователя, кг",   "print" =>  false),
    array("pole" => "extra_field_2",  "name" => "Тип велотренажера",                   "print" =>  false),
    array("pole" => "extra_field_3",  "name" => "Система нагружения",                  "print" =>  false),
    array("pole" => "extra_field_4",  "name" => "Вес маховика, кг",                    "print" =>  false),
    array("pole" => "extra_field_5",  "name" => "Вес брутто, кг",                      "print" =>  true),
    array("pole" => "extra_field_6",  "name" => "Длина упаковки, мм",                  "print" =>  true),
    array("pole" => "extra_field_7",  "name" => "Ширина упаковки, мм",                 "print" =>  true),
    array("pole" => "extra_field_8",  "name" => "Высота упаковки,мм",                  "print" =>  true),
    array("pole" => "extra_field_9",  "name" => "Максимальный вес пользователя, кг",   "print" =>  false),
    array("pole" => "extra_field_10", "name" => "Тип беговой дорожки",                 "print" =>  false),
    array("pole" => "extra_field_11", "name" => "Длина бегового полотна, мм",          "print" =>  false),
    array("pole" => "extra_field_12", "name" => "Ширина бегового полотна, мм",         "print" =>  false),
    array("pole" => "extra_field_13", "name" => "Максимальная скорость, км/ч",         "print" =>  false),
    array("pole" => "product_manufacturer_id",  "name" => "Производитель",             "print" =>  false)
);
// Массив какие Категории нам нужны
print "<h1>Все</h1>";
view_table($_array_data, array(87,313,86,88,89,147,257,258,232));

?>
</body>
</html>