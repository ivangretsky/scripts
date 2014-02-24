<?php
// Удалить лишнее
// UPDATE `vlif9_jshopping_products` SET `extra_field_3`='', `extra_field_17`='', `extra_field_2`='', `extra_field_10`='' WHERE `vendor_id`='25'
// Сколько угодноскрипт пусть парсит
set_time_limit (0);
function translitIt($str) 
{
    $tr = array(
	"А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
	"Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
	"Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
	"О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
	"У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
	"Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
	"Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
	"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
	"з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
	"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
	"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
	"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
	"ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", 
	" "=> "-", "."=> "",  ","=> "", "/"=> "-"
    );
    $newstr = strtr($str,$tr);
    $urlstr = preg_replace('/[^A-Za-z0-9_\-]/', '', $newstr );
	return $urlstr;
}
function cutString($string, $maxlen) {
    $len = (mb_strlen($string) > $maxlen)
	? mb_strripos(mb_substr($string, 0, $maxlen), ' ')
	: $maxlen
    ;
    $cutStr = mb_substr($string, 0, $len);
    return (mb_strlen($string) > $maxlen)
	? '' . $cutStr . '...'
	: '' . $cutStr . ''
    ;
}


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >

<head>

  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
    
    
    
<?php




$jprefix = 'vlif9';
//$destdb = 'tversport';
//$db = mysql_connect("localhost", "root", "12345678");

//$destdb = 'tversportru2';
//$db = mysql_connect("127.0.0.1", "root", "");

//$destdb = 'mskhsprtdb';
//$destdb = 'yarhsprtdb';
//$destdb = 'nnhsprtdb';
//$destdb = 'vlhsprtdb';
$destdb = 'tversportru2';
$db = mysql_connect("127.0.0.1", "root", "");
//$destdb = 'tversport';
//$db = mysql_connect("localhost", "root", "12345678");

if (!$db) {
echo "Не удается подключиться к БД";
}
else {
mysql_query('SET NAMES utf8');
mysql_select_db($destdb);

// Загрузка xml файла
if (file_exists('neotren-xml.xml')) {
	$xml = simplexml_load_file('neotren-xml.xml');
} else {
	exit('Не удалось открыть файл neotren-xml.xml.');
}


// Производители
$manufacturers['Oxygen']=20;
$manufacturers['Vision']=29;
$manufacturers['Horizon']=69;
$manufacturers['Johnson']=74;
$manufacturers['Matrix']=75;
$manufacturers['LiveStrong']=76;
$manufacturers['Body Solid']=70;
$manufacturers['Bronze Gym']=77;	
$manufacturers['LeMond']=78;	
$manufacturers['Carbon']=79;	

	
echo '<strong>Check category sequence</strong><br />';

// Категории
$newcategories[569]=83; //Беговые дорожки
$newcategories[570]=83; //домашние
$newcategories[571]=83; //полупрофессиональные
$newcategories[572]=278; //профессиональные	

$newcategories[573]=84; //Велотренажеры
$newcategories[574]=84; //домашние
$newcategories[575]=84; //полупрофессиональные
$newcategories[576]=312; //профессиональные	

$newcategories[577]=87; //Эллиптические тренажеры
$newcategories[578]=87; //домашние
$newcategories[579]=87; //полупрофессиональные
$newcategories[580]=313;//профессиональные
	
$newcategories[581]=86; //Степперы
$newcategories[582]=86; //домашние
$newcategories[583]=86; //профессиональные

$newcategories[584]=88; //Гребные тренажеры
$newcategories[585]=88; //домашние
$newcategories[586]=88; //профессиональные	
	
$newcategories[587]=0; //Универсальные тренажеры
$newcategories[588]=0; //домашние

$newcategories[592]=89; //Силовые тренажеры
$newcategories[593]=89; //домашние
$newcategories[594]=89; //профессиональные

$newcategories[595]=149; //Массажные столы
$newcategories[596]=257; //складные
$newcategories[597]=258; //стационарные

$newcategories[598]=0; //Допоборудование
$newcategories[599]=104; //Гантели и штанги
$newcategories[600]=114; //Аэробика
$newcategories[601]=0; //Электроника
	
$newcategories[602]=232; //Батуты
$newcategories[603]=232; //выносные
$newcategories[605]=0; //Аксессуары

$extimgquery='';
$flag = 0;

// Проверка на пропущенные категории и новые категории
foreach($xml->shop->categories->category as $category) {
	$newcatid = (int)$category->attributes()->id;
	if(!isset($newcategories[$newcatid])){
                // Если категории нету в списке
		echo 'New neotren category, id='.$newcatid;		
		echo '... <span style="color:red;">Add new category!</span><br />';
		$flag = 1;
	} else {
                // Если о категории знаем но ее нету
		if($newcategories[$newcatid]==0) { 
			echo 'Skipped neotren category, id='.$newcatid.'<br />';								
		}
	}
}

// Выдаем сообщение чтобы добавили категории
if($flag==1) {
 echo 'Please, add categories! ';	
 die();	
}

$upd_price=0;
$new_prods=0;
$skip_prods=0;
$old_prods=0;
$rem_prods=0;

// Продукция для обработки
$product_array = array();

// Вывести все нужные характеристики Стандартная
?><h1>Основная таблица</h1><table style="border: 2px solid black;display: none;"><th>Имя</th><th>Цена</th><th>Максимальный вес пользователя, кг</th><th>Тип беговой дорожки</th><th>Длина бегового полотна, мм</th>
    <th>Ширина бегового полотна, мм</th><th>Максимальная скорость, км/ч</th><th>Вес в брутто</th><th>Размер в сложенном виде</th><th>Производитель</th><?php

foreach($xml->shop->offers->offer as $offer) {

    // Выводим только нужные категории
    if(true){
      //  if($offer->categoryId==578||$offer->categoryId==579||$offer->categoryId==580)
        $product_array [] = $offer;
        ?><tr><?php
            ?><td style="border: 2px solid black"><?php print $offer->name."(".(int)$offer->attributes()->id.")";?></td><?php
            ?><td style="border: 2px solid black"><?php print $offer->price;?></td><?php
            
            // Выводим характеристики
            $bname = array("weight"=>"", "btype"=>"", "sizeb"=>"", "sizebd"=>"", "speed"=>"", "gweight"=>"", "ssize"=>""); // Чтобы показать если нет значения
            foreach($offer->attrs->attr as $attr) {

                if($attr->attributes()->key=='weight')   {$bname["weight"] = $attr;}
                elseif($attr->attributes()->key=='btype'||$attr->attributes()->key=='pozitsii'){$bname["btype"] = $attr;}
                elseif($attr->attributes()->key=='sizeb'){$bname["sizeb"] = $attr;$bname["sizebd"] = $attr;}
                elseif($attr->attributes()->key=='speed'){$bname["speed"] = $attr;}
                elseif($attr->attributes()->key=='gweight'){$bname["gweight"] = $attr;}
                elseif($attr->attributes()->key=='ssize'){$bname["ssize"] = $attr;}
            } 
            // Выводим в таблицу
            foreach($bname as $text) {if(!$text=="")print "<td style='border: 2px solid black'>".$text."</td>";else print "<td style='background:red;'>Пусто</td>";}
            //******************************************** Выводим характеристики
            ?><td style="border: 2px solid black"><?php print $offer->vendor;?></td><?php
        ?></tr><?php
    }
    // **************************************** Выводим только нужные категории
}

?></table><?php


print "Сколько будет проверенно: ".count($product_array);
// Главный парсинг

foreach($product_array as $offer) {
    $i = 0;
    
    foreach($offer->attrs->attr as $attr) {
       
        // Вес
        if($attr->attributes()->key=='weight'){
          $result = array(); 
          preg_match_all("/[0-9]+/", $attr, $result);
          $offer->attrs->attr[$i] = implode("",$result[0]);
        }
        
         // Вес маховика
        if($attr->attributes()->key=='mahovik'){
          $result = array(); 
          preg_match_all("/[0-9]+/", $attr, $result);
          $offer->attrs->attr[$i] = implode(".",$result[0]);
          // Делаем только первую цифру
          if(count(explode(".",$offer->attrs->attr[$i]))>1){
              $text_array = explode(".",$offer->attrs->attr[$i]);
              $offer->attrs->attr[$i] = $text_array[0].".".$text_array[1];
          }
        }
        
        
         // Длина и ширина
        if($attr->attributes()->key=='sizeb'){
          $result = array(); 
          preg_match_all("/[0-9]+/", $attr, $result);
       
         foreach($result as $n){$offer->attrs->attr[$i] = ($n[0])."-".($n[1]);}
        }
        
        
        // Максимальная скорость, км/ч
        if($attr->attributes()->key=='speed'){
          $result = array(); 
          preg_match_all("/[.0-9]+/", $attr, $result);
       
         foreach($result as $n){$offer->attrs->attr[$i] = $n[0]."-".$n[1];}
        }
        
        // Вес нетто
        if($attr->attributes()->key=='tweight'){
          $result = array(); 
          preg_match_all("/[0-9]+/", $attr, $result);
          $offer->attrs->attr[$i] = implode("",$result[0]);
        }
        
        // Вес в брутто
        if($attr->attributes()->key=='gweight'){
          $result = array(); 
          preg_match_all("/[0-9]+/", $attr, $result);
          $offer->attrs->attr[$i] = implode("",$result[0]);
        }
        
        // Длина шага
        if($attr->attributes()->key=='stepl'){
          $result = array(); 
          $data = explode("-", $attr);
          
          preg_match_all("/[0-9]+/", $attr);
          if(count($data)>1)
              preg_match_all("/[0-9]+/", $data[1], $result);
          else
              preg_match_all("/[0-9]+/", $data[0], $result);
          
          $offer->attrs->attr[$i] = implode("",$result[0]);
        }
        
        // Размер в сложенном виде
        if($attr->attributes()->key=='ssize'){
         $result = array(); 
         if(!strripos($attr, "(")) 
             preg_match_all("/[.0-9]+/", $attr, $result);
         else
             preg_match_all("/[.0-9]+/", $attr, $result);
         
         foreach($result as $n){
             if(count($n)==4)
                 $offer->attrs->attr[$i] = ($n[0]*10)."-".($n[1]*10)."-".($n[2]*10);
             else{
                 $offer->attrs->attr[$i] = ($n[0]*10)."-".($n[1]*10)."-";
                 $offer->attrs->attr[$i] .= $n[3]>$n[4]?($n[3]*10):($n[4]*10); 
             }
         }
         
  
         
        }
        
         $i++;
    }
    
}

// Вывести все нужные характеристики Нужная таблица
?><h1>Нужная таблица</h1><table style="border: 2px solid black"><th>Имя</th><th>Цена</th><th>Максимальный вес пользователя</th><th>Система нагружения</th><th>Ширина шага</th><th>Вес маховика</th><?php

if(true)foreach($product_array as $offer) {

    
         
            
            
    
    // Выводим только нужные категории
    if($offer->categoryId==578||$offer->categoryId==579||$offer->categoryId==580){
        ?><tr><?php
            ?><td style="border: 2px solid black"><?php print $offer->name."(".(int)$offer->attributes()->id.")";?></td><?php
            ?><td style="border: 2px solid black"><?php print $offer->price;?></td><?php
            
            // Выводим характеристики
            $bname = array("weight"=>"", "nagruz"=>"", "stepl"=>"", "mahovik"=>""); // Чтобы показать если нет значения
            foreach($offer->attrs->attr as $attr) {

                if($attr->attributes()->key=='weight')   {$bname["weight"] = $attr;}
                elseif($attr->attributes()->key=='nagruz'){$bname["nagruz"] = $attr;}
                elseif($attr->attributes()->key=='stepl'){$bname["stepl"] = $attr;}
                elseif($attr->attributes()->key=='mahovik'){$bname["mahovik"] = $attr;}
            } 
            // Выводим в таблицу
            foreach($bname as $text) {if(!$text=="")print "<td style='border: 2px solid black;'>".$text."</td>";else print "<td style='border: 2px solid black;background:red;'>Пусто</td>";}
            //******************************************** Выводим характеристики

            
            // Проверяем на состояние товара
        /*    $prod_ean = 'neotren-'.(int)$offer->attributes()->id; // алиас
            $result = mysql_query("SELECT `product_id` FROM `".$jprefix."_jshopping_products` WHERE `product_ean`='".mysql_real_escape_string($prod_ean)."';");
            if(mysql_num_rows($result)>0)
                print "<td style='border: 2px solid black'>Есть в базе</td>";
            else
                print "<td style='background:red;border: 2px solid black'>Нету в базе</td>";
            */
        ?></tr><?php
    }
    // **************************************** Выводим только нужные категории
}

?></table><?php

}

// Первоначальное обновление характеристик беговых дорожек(true = ВКЛ)
if(true) {
      
 foreach($product_array as $offer) {
         // Если брутто есть то не трогаем его
         $dataf5 = mysql_query("SELECT `extra_field_1`,`extra_field_2`,`extra_field_3`,`extra_field_4`,`extra_field_5`,`extra_field_6`,`extra_field_7`, `extra_field_8`,`extra_field_9`,`extra_field_10`,`extra_field_11`,`extra_field_12`,`extra_field_13`,`extra_field_14`,`extra_field_15`,`extra_field_16`,`extra_field_17` FROM `".$jprefix."_jshopping_products` WHERE `product_ean`='".mysql_real_escape_string('neotren-'.(int)$offer->attributes()->id)."'");
         if (!$dataf5) echo mysql_errno($db) . ": " . mysql_error($db);
// Эллиптические
if(true)if($offer->categoryId==578||$offer->categoryId==579||$offer->categoryId==580){
            $str = "";
            $data_netto = "";
            $bool_brutto = false;
            foreach($offer->attrs->attr as $attr) {
                
                // Максимальный весь пользователя
                if($attr->attributes()->key=='weight')   {if(strlen ($dataf5[13])==0)$str .= "`extra_field_14`='".mysql_real_escape_string($attr)."', ";}
               // Система нагружения
                 elseif($attr->attributes()->key=='nagruz'){
                    
                    if(stripos($attr, "электромагнитная") !== FALSE) { if(strlen ($dataf5[16])==0)  $str .= "`extra_field_17`='".mysql_real_escape_string(11)."', ";}
                        else
                    if(stripos($attr, "магнитная")        !== FALSE)   if(strlen ($dataf5[16])==0)  $str .= "`extra_field_17`='".mysql_real_escape_string(10)."', ";
                    if(stripos($attr, "электромаг")       !== FALSE)   if(strlen ($dataf5[16])==0)  $str .= "`extra_field_17`='".mysql_real_escape_string(11)."', ";
                }
                // Длина шага
                 elseif($attr->attributes()->key=='stepl'){
                    if(strlen ($dataf5[14])==0)$str .= "`extra_field_15`='".mysql_real_escape_string($attr)."', ";
                }
                // Вес маховика, кг
                elseif($attr->attributes()->key=='mahovik'){
                    if(strlen ($dataf5[15])==0)$str .= "`extra_field_16`='".mysql_real_escape_string(mysql_real_escape_string($attr))."', ";
                  
                }
                // Вес нетто
                elseif($attr->attributes()->key=='tweight'){
                    $data_netto = $attr;
                }
                // Вес брутто, кг
                elseif($attr->attributes()->key=='gweight'){
                    if(strlen ($dataf5[4])==0)$str .= "`extra_field_5`='".mysql_real_escape_string($attr)."', ";
                    if(strlen ($dataf5[4])==0)$bool_brutto = true;
                }
                // Длина Ширина Высота
                elseif($attr->attributes()->key=='ssize'){
                    $_array = explode("-", $attr);
                    if(strlen ($dataf5[5])==0)$str .= "`extra_field_6`='".mysql_real_escape_string($_array[0])."', ";
                    if(strlen ($dataf5[6])==0)$str .= "`extra_field_7`='".mysql_real_escape_string($_array[1])."', ";
                    if(strlen ($dataf5[7])==0) $str .= "`extra_field_8`='".mysql_real_escape_string($_array[2])."', ";
                }
              
 }
 // Добавить вес нетто + 5
            if((!$bool_brutto)&&($data_netto > 0)) {
		     $str .= "`extra_field_5`='".mysql_real_escape_string($data_netto+5)."', ";
            }  
            
            
            //******************************************** Выводим характеристики
            // Обновляем в базе
            $prod_ean = 'neotren-'.(int)$offer->attributes()->id;
           
           if($str!=""){
               $str .= "`product_price`='".mysql_real_escape_string($offer->price)."'";
               $result2 = mysql_query("UPDATE `".$jprefix."_jshopping_products` SET $str WHERE `product_ean`='".mysql_real_escape_string($prod_ean)."';");
               $str = "";
           }
} // **************************************************************************************************************** // Ветка Беговые дорожки - конец            
                 
  
// Вело
if($offer->categoryId==574||$offer->categoryId==575||$offer->categoryId==576){
            // Список что будем обновлять
            $str = "";
            $data_netto = "";
            $bool_brutto = false;
                foreach($offer->attrs->attr as $attr) {
               
                // Максимальный вес пользователя, кг
                if($attr->attributes()->key=='weight')   {    if(strlen ($dataf5[0])==0)$str .= "`extra_field_1`='".mysql_real_escape_string($attr)."', ";}
                // Тип велотренажера
                elseif($attr->attributes()->key=='posadka'){
                    if(stripos($attr, "вертикальная")   !== FALSE) if(strlen ($dataf5[1])==0)$str .= "`extra_field_2`='".mysql_real_escape_string(1)."', ";
                    if(stripos($attr, "горизонтальная") !== FALSE) if(strlen ($dataf5[1])==0)$str .= "`extra_field_2`='".mysql_real_escape_string(2)."', ";                     
                    
                    
                }
                // Система нагружения
                 elseif($attr->attributes()->key=='nagruz'){
                    if(stripos($attr, "механическая")     !== FALSE) if(strlen ($dataf5[2])==0)$str .= "`extra_field_3`='".mysql_real_escape_string(3)."', ";
                    if(stripos($attr, "электромаг") !== FALSE) {if(strlen ($dataf5[2])==0)$str .= "`extra_field_3`='".mysql_real_escape_string(5)."', ";}
                    else
                    if(stripos($attr, "магнитная")        !== FALSE) if(strlen ($dataf5[2])==0)$str .= "`extra_field_3`='".mysql_real_escape_string(4)."', ";
                }
                // Вес маховика, кг
                elseif($attr->attributes()->key=='mahovik'){
                    if(strlen ($dataf5[0])==0)$str .= "`extra_field_4`='".mysql_real_escape_string(mysql_real_escape_string($attr))."', ";
                  
                }
                // Вес нетто
                elseif($attr->attributes()->key=='tweight'){
                    $data_netto = $attr;
                }
                // Вес брутто, кг
                elseif($attr->attributes()->key=='gweight'){
                    if(strlen ($dataf5[4])==0)$str .= "`extra_field_5`='".mysql_real_escape_string($attr)."', ";
                    if(strlen ($dataf5[4])==0)$bool_brutto = true;
                }
                // Длина Ширина Высота
                elseif($attr->attributes()->key=='ssize'){
                    $_array = explode("-", $attr);
                    if(strlen ($dataf5[5])==0)$str .= "`extra_field_6`='".mysql_real_escape_string($_array[0])."', ";
                    if(strlen ($dataf5[6])==0)$str .= "`extra_field_7`='".mysql_real_escape_string($_array[1])."', ";
                    if(strlen ($dataf5[7])==0)$str .= "`extra_field_8`='".mysql_real_escape_string($_array[2])."', ";
                }
                
            }
            // Добавить вес нетто + 5
            if((!$bool_brutto)&&($data_netto > 0)) {
		     $str .= "`extra_field_5`='".mysql_real_escape_string($data_netto+5)."', ";
            }  
            
            
            //******************************************** Выводим характеристики
            // Обновляем в базе
            $prod_ean = 'neotren-'.(int)$offer->attributes()->id;
           
           if($str!=""){
               $str .= "`product_price`='".mysql_real_escape_string($offer->price)."'";
               $result2 = mysql_query("UPDATE `".$jprefix."_jshopping_products` SET $str WHERE `product_ean`='".mysql_real_escape_string($prod_ean)."';");
               $str = "";
           }
}
                
// Беговые
if(true)if($offer->categoryId==570||$offer->categoryId==571||$offer->categoryId==572){
            // Список что будем обновлять
            $str = "";
            $data_netto = 0;
            $bool_brutto = false;
                foreach($offer->attrs->attr as $attr) {
                
                if($attr->attributes()->key=='weight')   {
                    if(strlen ($dataf5[8])==0)$str .= "`extra_field_9`='".mysql_real_escape_string($attr)."', ";
                }
                
                elseif($attr->attributes()->key=='btype'||$attr->attributes()->key=='pozitsii'){
                    // Приводим все к низу, чтобы можно было ставить id правильный
                    if(stripos($attr, "механическая")     !== FALSE)if(strlen ($dataf5[9])==0) $str .= "`extra_field_10`='".mysql_real_escape_string(6)."', ";
                    if(stripos($attr, "электромаг") !== FALSE){if(strlen ($dataf5[9])==0) $str .= "`extra_field_10`='".mysql_real_escape_string(8)."', ";}
                    else
                    if(stripos($attr, "магнитная")        !== FALSE)if(strlen ($dataf5[9])==0) $str .= "`extra_field_10`='".mysql_real_escape_string(7)."', ";
                    if(stripos($attr, "электрическая")    !== FALSE)if(strlen ($dataf5[9])==0) $str .= "`extra_field_10`='".mysql_real_escape_string(9)."', ";
                }
                elseif($attr->attributes()->key=='tweight'){
                    $data_netto = $attr;
                }
                elseif($attr->attributes()->key=='sizeb'){
                    $_array = explode("-", $attr);
                    if(strlen ($dataf5[10])==0)$str .= "`extra_field_11`='".mysql_real_escape_string($_array[0])."', ";
                    if(strlen ($dataf5[11])==0)$str .= "`extra_field_12`='".mysql_real_escape_string($_array[1])."', ";
                }
                
                elseif($attr->attributes()->key=='speed'){
                   $_array = explode("-", $attr);
                   if(strlen ($dataf5[12])==0)$str .= "`extra_field_13`='".mysql_real_escape_string($_array[1])."', ";
                }
                
                elseif($attr->attributes()->key=='gweight'){
                    if(strlen ($dataf5[4])==0)$str .= "`extra_field_5`='".mysql_real_escape_string($attr)."', ";
                    $bool_brutto = true;
                }
                
                elseif($attr->attributes()->key=='ssize'){
                    $_array = explode("-", $attr);
                    if(strlen ($dataf5[5])==0)$str .= "`extra_field_6`='".mysql_real_escape_string($_array[0])."', ";
                    if(strlen ($dataf5[6])==0)$str .= "`extra_field_7`='".mysql_real_escape_string($_array[1])."', ";
                    if(strlen ($dataf5[7])==0)$str .= "`extra_field_8`='".mysql_real_escape_string($_array[2])."', ";
                }
                
            } 
            
            // Добавить вес нетто + 5
            if((!$bool_brutto)&&($data_netto > 0)) {
		     $str .= "`extra_field_5`='".mysql_real_escape_string($data_netto+5)."', ";
            }  
            
            
            //******************************************** Выводим характеристики
            // Обновляем в базе
            $prod_ean = 'neotren-'.(int)$offer->attributes()->id;
           
           if($str!=""){
               $str .= "`product_price`='".mysql_real_escape_string($offer->price)."'";
               $result2 = mysql_query("UPDATE `".$jprefix."_jshopping_products` SET $str WHERE `product_ean`='".mysql_real_escape_string($prod_ean)."';");
               $str = "";
           }
           
           }
    
    
 }
//die('Beg dorozhki upd finished');
}

// Добавление новых товаров и обновление цен старых товаров
$_countadd = 0;
foreach($product_array as $offer) {
    $product = $offer;
    // Выводим только нужные категории
    if(true){
            // Список что будем обновлять
            $str = "";
            // Для брутто
            $bool_brutto = false;
			$data_netto = 0;
			
                        
                        
            // Эллиптические
            if($offer->categoryId==578||$offer->categoryId==579||$offer->categoryId==580)
            foreach($offer->attrs->attr as $attr) {
                // Максимальный весь пользователя
                if($attr->attributes()->key=='weight')   {$str .= "`extra_field_14`='".mysql_real_escape_string($attr)."', ";}
                // Система нагружения
                 elseif($attr->attributes()->key=='nagruz'){
                    if(stripos($attr, "электромаг") !== FALSE) $str .= "`extra_field_17`='".mysql_real_escape_string(11)."', ";
                    else
                    if(stripos($attr, "магнитная")        !== FALSE) $str .= "`extra_field_17`='".mysql_real_escape_string(10)."', ";
                }
                // Длина шага
                 elseif($attr->attributes()->key=='stepl'){
                    $str .= "`extra_field_15`='".mysql_real_escape_string($attr)."', ";
                }
                // Вес маховика, кг
                elseif($attr->attributes()->key=='mahovik'){
                    $str .= "`extra_field_16`='".mysql_real_escape_string(mysql_real_escape_string($attr))."', ";
                  
                }
                // Вес нетто
                elseif($attr->attributes()->key=='tweight'){
                    $data_netto = $attr;
                }
                // Вес брутто, кг
                elseif($attr->attributes()->key=='gweight'){
                    $str .= "`extra_field_5`='".mysql_real_escape_string($attr)."', ";
                    $bool_brutto = true;
                }
                // Длина Ширина Высота
                elseif($attr->attributes()->key=='ssize'){
                    $_array = explode("-", $attr);
                    $str .= "`extra_field_6`='".mysql_real_escape_string($_array[0])."', ";
                    $str .= "`extra_field_7`='".mysql_real_escape_string($_array[1])."', ";
                    $str .= "`extra_field_8`='".mysql_real_escape_string($_array[2])."', ";
                }
            } // **************************************************************************************************************** // Ветка Беговые дорожки - конец            
                        
                        
            // Ветка Беговые дорожки
            if($offer->categoryId==569||$offer->categoryId==570||$offer->categoryId==571||$offer->categoryId==572)
            foreach($offer->attrs->attr as $attr) {
                if($attr->attributes()->key=='weight')   {$str .= "`extra_field_9`='".mysql_real_escape_string($attr)."', ";}
                
                elseif($attr->attributes()->key=='btype'||$attr->attributes()->key=='pozitsii'||$attr->attributes()->key=='posadka'){
                    if(stripos($attr, "механическая")     !== FALSE) $str .= "`extra_field_10`='".mysql_real_escape_string(6)."', ";
                    if(stripos($attr, "электромаг") !== FALSE) $str .= "`extra_field_10`='".mysql_real_escape_string(8)."', ";
                        else
                    if(stripos($attr, "магнитная")        !== FALSE) $str .= "`extra_field_10`='".mysql_real_escape_string(7)."', ";
                    if(stripos($attr, "электрическая")    !== FALSE) $str .= "`extra_field_10`='".mysql_real_escape_string(9)."', ";
                }
                elseif($attr->attributes()->key=='tweight'){
                    $data_netto = $attr;
                }
                elseif($attr->attributes()->key=='sizeb'){
                    $_array = explode("-", $attr);
                    $str .= "`extra_field_11`='".mysql_real_escape_string($_array[0])."', ";
                    $str .= "`extra_field_12`='".mysql_real_escape_string($_array[1])."', ";
                    $bool_brutto = true;
                }
                
                elseif($attr->attributes()->key=='speed'){
                   $_array = explode("-", $attr);
                    $str .= "`extra_field_13`='".mysql_real_escape_string($_array[1])."', ";
                }
                
                elseif($attr->attributes()->key=='gweight'){$str .= "`extra_field_5`='".mysql_real_escape_string($attr)."', ";}
                
                elseif($attr->attributes()->key=='ssize'){
                    $_array = explode("-", $attr);
                    $str .= "`extra_field_6`='".mysql_real_escape_string($_array[0])."', ";
                    $str .= "`extra_field_7`='".mysql_real_escape_string($_array[1])."', ";
                    $str .= "`extra_field_8`='".mysql_real_escape_string($_array[2])."', ";
                }
                
            } // **************************************************************************************************************** // Ветка Беговые дорожки - конец
            
            
             
          if($offer->categoryId==573||$offer->categoryId==574||$offer->categoryId==575||$offer->categoryId==576)// Ветка Велотренажеры             
					/*
					||
					$offer->categoryId==577||$offer->categoryId==578||$offer->categoryId==579||$offer->categoryId==580||// Эллиптические тренажеры
                    $offer->categoryId==581||$offer->categoryId==582||$offer->categoryId==579||$offer->categoryId==583||// Степперы
                    $offer->categoryId==584||$offer->categoryId==585||$offer->categoryId==586||//Гребные тренажеры
                    $offer->categoryId==587||$offer->categoryId==588||//Универсальные тренажеры
                    $offer->categoryId==592||$offer->categoryId==593||$offer->categoryId==594||//Силовые тренажеры
                    $offer->categoryId==595||$offer->categoryId==596||$offer->categoryId==597|| //Массажные столы
                    $offer->categoryId==598||$offer->categoryId==599||$offer->categoryId==600||$offer->categoryId==601||//Допоборудование
                    $offer->categoryId==602||$offer->categoryId==603||$offer->categoryId==605) //Батуты
					*/
            foreach($offer->attrs->attr as $attr) {
                // Максимальный вес пользователя, кг
                if($attr->attributes()->key=='weight')   {$str .= "`extra_field_1`='".mysql_real_escape_string($attr)."', ";}
                // Тип велотренажера
                elseif($attr->attributes()->key=='posadka'){
                    if(stripos($attr, "вертикальный")   !== FALSE)   $str .= "`extra_field_2`='".mysql_real_escape_string(1)."', ";
                    if(stripos($attr, "горизонтальный") !== FALSE)   $str .= "`extra_field_2`='".mysql_real_escape_string(2)."', ";
                }
                // Система нагружения
                 elseif($attr->attributes()->key=='nagruz'){
                    if(stripos($attr, "механическая")     !== FALSE) $str .= "`extra_field_3`='".mysql_real_escape_string(3)."', ";
                    
                    if(stripos($attr, "электромаг") !== FALSE) $str .= "`extra_field_3`='".mysql_real_escape_string(5)."', ";
                    else
                    if(stripos($attr, "магнитная")        !== FALSE) $str .= "`extra_field_3`='".mysql_real_escape_string(4)."', ";
                }
                // Вес маховика, кг
                elseif($attr->attributes()->key=='mahovik'){
                    $str .= "`extra_field_4`='".mysql_real_escape_string(mysql_real_escape_string($attr))."', ";
                  
                }
                // Вес нетто
                elseif($attr->attributes()->key=='tweight'){
                    $data_netto = $attr;
                }
                // Вес брутто, кг
                elseif($attr->attributes()->key=='gweight'){
                    $str .= "`extra_field_5`='".mysql_real_escape_string($attr)."', ";
                    $bool_brutto = true;
                }
                // Длина Ширина Высота
                elseif($attr->attributes()->key=='ssize'){
                    $_array = explode("-", $attr);
                    $str .= "`extra_field_6`='".mysql_real_escape_string($_array[0])."', ";
                    $str .= "`extra_field_7`='".mysql_real_escape_string($_array[1])."', ";
                    $str .= "`extra_field_8`='".mysql_real_escape_string($_array[2])."', ";
                }
                
            } // ****************************************************************************************************************
           
           if((!$bool_brutto)&&($data_netto > 0)) {
		     $str .= "`extra_field_5`='".mysql_real_escape_string($data_netto+5)."', ";
		   }    
            
            $str .= "`product_price`='".mysql_real_escape_string($offer->price)."'";
       
            
            
    $prod_neotren_id = (int)$product->attributes()->id;
	$prod_neotren_cat = (int)$product->categoryId;
	$prod_cat = $newcategories[$prod_neotren_cat];
	$prod_ean = 'neotren-'.$prod_neotren_id; // алиас
	$prod_eans[] = $prod_ean;
	$prod_price = (int)$product->price;
	$prod_img = $product->picture;
	$prod_alias = strtolower(translitIt($product->name)).'-'.$prod_neotren_id;
	$prod_descr = html_entity_decode($product->description);
	$prod_sdescr = strip_tags($product->description);
	$prod_sdescr = cutString($prod_sdescr, 400);
        
        // Чтобы проверить то что мне нужно
       // if($offer->categoryId==578||$offer->categoryId==579||$offer->categoryId==580){}else{continue;};
        
	if(isset($manufacturers["$product->vendor"])) {
	 $prod_manuf = $manufacturers["$product->vendor"];
	} else {
		echo '<span style="color:red;">Add manufacturer <strong>'.$product->vendor.'</strong></span>';
		die();		
	}   
            
       
        $result = mysql_query("SELECT `product_price`,`product_id` FROM `".$jprefix."_jshopping_products` WHERE `product_ean`='".mysql_real_escape_string($prod_ean)."';");
	if (!$result) {
		echo mysql_errno($db) . ": " . mysql_error($db);
	}
        
        
    if (mysql_num_rows($result) >0){
	    while($row=mysql_fetch_row($result)) {
			if(((int)$row[0])!=$prod_price) {
				$result2 = mysql_query("UPDATE `".$jprefix."_jshopping_products` SET `product_price`=$prod_price, `min_price`= $prod_price WHERE `product_ean`='".mysql_real_escape_string($prod_ean)."';");
				if (!$result2) {
					echo mysql_errno($db) . ": " . mysql_error($db);
				} else {
					$upd_price+=1;
				}				
			} else {
				$old_prods+=1;		
			}	
			
		}        
    } else {
		//Adding new product
		if(($prod_manuf>0)&&($prod_cat>0)) {
                    $_countadd++;
                    print "add ".$product->name."=".(int)$product->attributes()->id."/<br/>";
			//Attributes
			    $attrtable = '';
				if(isset($product->attrs)) {				
				  foreach($product->attrs->attr as $prodattr) {
				  $attrtable .= '<tr><td>'.$prodattr->attributes()->name.'</td><td>'.$prodattr.'</td></tr>';					
				}
				if($attrtable!='') {
				  $attrtable = '<p style="font-weight:bold;">Характеристики:</p><table class="prodattrs">'.$attrtable.'</table>';		
				}
				
			}		
						
			$query1="INSERT into `".$jprefix."_jshopping_products` (
			`product_ean`, `product_quantity`, `unlimited`, `product_availability`, `product_date_added`, `date_modify`, `product_publish`, `product_tax_id`,
			`product_template`, `product_url`, `product_old_price`, `product_buy_price`, `product_price`, `min_price`, `different_prices`, `product_weight`,
			`product_thumb_image`, `product_name_image`, `product_full_image`, `product_manufacturer_id`, `product_is_add_price`, `average_rating`, `reviews_count`, `delivery_times_id`,
			`hits`, `weight_volume_units`, `basic_price_unit_id`, `label_id`, `vendor_id`, `name_en-GB`, `alias_en-GB`, `short_description_en-GB`,
			`description_en-GB`, `meta_title_en-GB`, `meta_description_en-GB`, `meta_keyword_en-GB`, `name_ru-RU`, `alias_ru-RU`, `short_description_ru-RU`, `description_ru-RU`,
			`meta_title_ru-RU`, `meta_description_ru-RU`, `meta_keyword_ru-RU`, `parent_id`, `currency_id`, `access`, `add_price_unit_id`) VALUES (
			'".mysql_real_escape_string($prod_ean)."', 1, 1, '', now(), now(), 1, 1,
			'default', '', 0, 0, $prod_price, $prod_price, 0, 0,
			'awaiting', '', '".mysql_real_escape_string($prod_img)."',$prod_manuf,0,0,0,0,
			0,0,0,0,25,'','','',
			'',	'',	'',	'',	'".mysql_real_escape_string($product->name)."','".mysql_real_escape_string($prod_alias)."',	'".mysql_real_escape_string($prod_sdescr)."','".mysql_real_escape_string($prod_descr.$attrtable)."',
			'',	'',	'',0,2,1,0)";	
			$result2 = mysql_query($query1);
			if (!$result2) {
				echo 'ERROR: '.$query1.'<br />';
				echo mysql_errno($db) . ": " . mysql_error($db);
				die();
			} else {
				$last_prod_id = mysql_insert_id();
				$new_prods+=1;
				$query2="INSERT INTO `".$jprefix."_jshopping_products_to_categories` SET `product_id`=".mysql_insert_id().", `category_id`=$prod_cat, `product_ordering`=0";
				$result2 = mysql_query($query2);
				if (!$result2) {
					echo 'ERROR: '.$query2.'<br />';
					echo mysql_errno($db) . ": " . mysql_error($db);
					die();
				}
                // Обновление созданного товара
                  
                $result2 = mysql_query("UPDATE `".$jprefix."_jshopping_products` SET $str WHERE `product_ean`='".mysql_real_escape_string($prod_ean)."';");
				if (!$result2) {
					echo 'ERROR: Update Error<br />';
					echo mysql_errno($db) . ": " . mysql_error($db);
					die();
				}	
                                
			}
						
			//ExtImages
			if(isset($product->extImages)) {
				foreach($product->extImages->image as $prodimage) {
					$result2 = mysql_query("INSERT INTO `".$jprefix."_jshopping_products_images` (`product_id`, `image_thumb`, `image_name`, `image_full`, `name`, `ordering`) 
					VALUES ($last_prod_id,'awaiting','','".mysql_real_escape_string($prodimage)."','',0) ");
					if (!$result2) {
					 echo mysql_errno($db) . ": " . mysql_error($db);
					 die();
					}										
				}				
			}	
				
			if(isset($product->salesArguments)) {
				foreach($product->salesArguments->argument as $prodarg) {
					$prodimage = $prodarg->image;
					/*$extimgquery .= "INSERT INTO `".$jprefix."_jshopping_products_images` (`product_id`, `image_thumb`, `image_name`, `image_full`, `name`, `ordering`) VALUES ($last_prod_id,'awaiting','','".mysql_real_escape_string($prodimage)."','".mysql_real_escape_string($prodarg->title)."',0);\r\n";		*/
					$extimgquery = "INSERT INTO `".$jprefix."_jshopping_products_images` (`product_id`, `image_thumb`, `image_name`, `image_full`, `name`, `ordering`) VALUES ($last_prod_id,'awaiting','','".mysql_real_escape_string($prodimage)."','".mysql_real_escape_string($prodarg->title)."',0);";		
					$result2 = mysql_query($extimgquery);
					if (!$result2) {
					    echo 'ERROR: '.$extimgquery.'<br />';						
						echo mysql_errno($db) . ": " . mysql_error($db);
						die();
					}					
				}
			}												
		} else {
			//$skip_prods+=1;			
		}
	}
            
            
            if (!$result2) echo mysql_errno($db) . ": " . mysql_error($db);
          //  print "UPDATE `".$jprefix."_jshopping_products` SET $str `min_price`= $prod_price WHERE `product_ean`='".mysql_real_escape_string($prod_ean)."';";

    }
    
}

print "<br/>Общее кол-".count($product_array).'<br />';
echo 'Products updated: '.$upd_price.'<br />';
echo 'Products unchanged: '.$old_prods.'<br />';
echo 'Products removed: '.$rem_prods.'<br />';
echo 'New products added: '.$new_prods.'<br />';
echo 'New products skipped: '.$skip_prods.'<br />';
echo 'DONE<br />';

?>

    
</body>
</html>