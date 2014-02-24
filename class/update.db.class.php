<?php
include_once '../class/csv.parsel.dxlab.class.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of update
 *
 * @author admin
 */
class updateDB {
    // Имя базы данных
    private $db    = "tversportru2";
    // Адресс
    private $host = "127.0.0.1";
    // Юзер
    private $user  = "root";
    // Пароль
    private $pass  = "";
    // Таблица
    private $table = "`groupvk`";
    // Объект db
    private $db_link;
    // Объект $csv
    private $csv;
    
    function updateDB($config){
        $this->db    = $config["db"];
        $this->host  = $config["host"];
        $this->user  = $config["user"];
        $this->pass  = $config["pass"];
        $this->table = $config["table"];
        
        $this->db_link = mysql_connect($this->host, $this->user, $this->pass);
        if (!$this->db_link) {
            die ("Не удается подключиться к БД");
        }
        else {
            mysql_query('SET NAMES utf8');
            mysql_select_db($this->db);
        }
    }
    
    
    // Обновляем таблицу с информацией со страниц
    public function updateCsvToTable($FileName, $_table){
        // Парсим csv файл
        $parselCsv = new csv();
        $arrayDataCsv = $parselCsv->getArray($FileName, array("name", "price", "id"));
        
        
        
        // Вытягиваем данные из таблицы
        $result = mysql_query("SELECT * FROM `".$this->table."`");
        if (!$result) {die (mysql_errno($this->db_link) . ": " . mysql_error($this->db_link));}
        
        $i = 0;
        if (mysql_num_rows($result) >0){
	    while($row =  mysql_fetch_object($result)) {
                        // Переменные для сверки
                        $article = "$$$$$";
                        $array = explode("&", $row->{"param"});
                        // Место для обновления товаров
                        foreach ($array as $p){
                          $line_param = explode(":", $p);
                          if(stripos($line_param[0], "Артикул") !== FALSE) $article = trim($line_param[1]);
                        }
                        
                        // Цикл перебор всех полей из csv файла
                        foreach ($arrayDataCsv as &$info){
                            if($info["name"]==$article){ 
                               $i++;
                               $price = array(); 
                               preg_match_all("/[0-9]+/", $info["price"], $price);
                               //print $i."-".$info["name"]."==".$article."<br/>";
                               $update = mysql_query("UPDATE `".$_table."` SET `cat` = '".$info["id"]."', `price` = '".implode("",$price[0])."' WHERE `id` =".$row->{"id"});
                               if (!$update) {die (mysql_errno($this->db_link) . ": " . mysql_error($this->db_link));}
                               $info["status"] = 1;
                            }
                               
                        }       
        
           }
        }
        
        // Добавляем товары в базу которые не нашли на сайте
        $in = 0;
        $o  = 0;
        if(false)foreach ($arrayDataCsv as &$info){
            $o++;
            if($info["status"]==0){
                $in++;
                $insert = mysql_query("INSERT INTO `csvpush` (`article`, `cat`)VALUES ('".$info["name"]."',  '".$info["id"]."')");
                if (!$insert) {die (mysql_errno($this->db_link) . ": " . mysql_error($this->db_link));}
                //print $i." - ".$info["name"]."<br/>";
            }
        }
        
        print "Обновленные: ".$i."</br>";
        print "Не добавленные: ".$in."</br>";
        print "Общее колличество: ".$o."<br/>";
        
        
    }    
    
    
    // Заливаем все на спорт тверь
    public function updateDBTverSport(){
        
        // Категории
        $array_cat = array(11=>388, 12=>389, 13=>390, 14=>391,
                           15=>388, 16=>391, 17=>392, 18=>393, 
                           19=>394, 21=>204,
                           22=>204, 23=>203, 24=>395, 31=>396,
                           32=>397, 33=>396, 41=>398, 51=>399,
                           52=>400, 53=>401, 54=>401, 55=>401,
                           61=>402, 62=>403, 63=>403, 64=>404,
                           65=>404, 66=>405, 67=>406, 68=>407,
                           69=>402, 70=>364, 71=>332, 72=>284,
                           81=>408, 82=>408, 83=>409, 85=>408,
                           87=>408, 88=>409
                           );
        
       // Производители
       $array_manuf = array(11=>"Rapala", 12=>"Rapala", 13=>"Rapala", 14=>"Blue Fox",
                            15=>"Storm", 16=>"Luhr Jensen", 17=>"Trigger X", 18=>"Dynamite Baits", 
                            19=>"Williamson", 21=>"Shimano",
                            22=>"G.Loomis", 23=>"Shimano", 24=>"Teho", 31=>"Sufix",
                            32=>"Power Pro", 33=>"Shimano", 41=>"VMC", 51=>"Plano",
                            52=>"Plano", 53=>"Rapala", 54=>"Shimano", 55=>"G.Loomis",
                            61=>"Rapala", 62=>"Marttiini", 63=>"Rapala", 64=>"Rapala",
                            65=>"Shimano", 66=>"Rapala", 67=>"", 68=>"Ace",
                            69=>"Shimano", 70=>"MarCum", 71=>"HumminBird", 72=>"Minn Kota",
                            81=>"Rapala ProWear", 82=>"Shimano", 83=>"SeaFox", 85=>"G.Loomis",
                            87=>"Nexus", 88=>"Sundridge"
                            );
        
        
       // Производители
       $array_manuf_id = array("Rapala"=>150, "Blue Fox"=>141,
                            "Storm"=>154, "Luhr Jensen"=>144, "Trigger X"=>158, "Dynamite Baits"=>142, 
                            "Williamson"=>160, "Shimano"=>153,
                            "G.Loomis"=>143, "Teho"=>153, "Sufix"=>155,
                            "Power Pro"=>149, "VMC"=>159,
                            "Plano"=>148, "Marttiini"=>146, ""=>"", "Ace"=>140, "MarCum"=>145, "HumminBird"=>110,
                            "Minn Kota"=>147,
                            "Rapala ProWear"=>151, "SeaFox"=>152, 
                            "Nexus"=>64, "Sundridge"=>156
                            );
        
        // Вытягиваем данные из таблицы
        $result = mysql_query("SELECT * FROM `siteinfo`");
        if (!$result) {die (mysql_errno($this->db_link) . ": " . mysql_error($this->db_link));}
        
        mysql_select_db("tversportru2");
        $jprefix = 'vlif9';
        $i = 0;
        if (mysql_num_rows($result) >0){
	    while($row =  mysql_fetch_object($result)) {
                if($row->{"cat"}!="0"){
                    if(!empty($array_cat[$row->{"cat"}])){
                        // Тут добавляем товар в базу
                        
                        
                        // Переменные для сверки
                        $prod_ean = "$$$$$";
                        $array = explode("&", $row->{"param"});
                        // Место для обновления товаров
                        foreach ($array as $p){
                          $line_param = explode(":", $p);
                          if(stripos($line_param[0], "Артикул") !== FALSE) $prod_ean = trim($line_param[1]);
                        }
                        
                        // Цена
                        $price = array(); 
                        preg_match_all("/[0-9]+/", $row->{"price"}, $price);
                        $prod_price = implode("", $price[0]);
                        
                        // Публикация
                        $public = 1;
                        if($prod_price=="0"||$prod_price=="")$public = 0;
             
                        // Картинка
                        $prod_img = $row->{"img"};
                        
                        // Производитель
                        $prod_manuf = 0;
                        if(!empty($array_manuf[$row->{"cat"}]))
                            if(!empty($array_manuf_id[$array_manuf[$row->{"cat"}]]))
                                $prod_manuf = $array_manuf_id[$array_manuf[$row->{"cat"}]];
                         
                        // Имя
                        $productName = $row->{"title"};
                        
                        // Сслыка на товар
                        $prod_alias = strtolower($this->translitIt($productName)).'-'.mt_rand(100, 9999);
                        
                        // Категория
                        $prod_cat = $array_cat[$row->{"cat"}];
                        
                        // Описание
                        $prod_descr = html_entity_decode($row->{"desc"});
                        $prod_sdescr = strip_tags($row->{"desc"});
                        $prod_sdescr = $this->cutString($prod_sdescr, 400);
                        
                        
                                // Атрибуты
                                $attrtable = '';
				foreach ($array as $p){
                                $line_param = explode(":", $p);
				$attrtable .= '<tr><td>'.$line_param[0].'</td><td>'.$line_param[1].'</td></tr>';					
				}
				if($attrtable!='') {
				  $attrtable = '<p style="font-weight:bold;">Характеристики:</p><table class="prodattrs">'.$attrtable.'</table>';		
				}
                                
                                
                        
                        // Формаруем запрос
                        $query1 = "INSERT into `".$jprefix."_jshopping_products` (
			`product_ean`, `product_quantity`, `unlimited`, `product_availability`, `product_date_added`, `date_modify`, `product_publish`, `product_tax_id`,
			`product_template`, `product_url`, `product_old_price`, `product_buy_price`, `product_price`, `min_price`, `different_prices`, `product_weight`,
			`product_thumb_image`, `product_name_image`, `product_full_image`, `product_manufacturer_id`, `product_is_add_price`, `average_rating`, `reviews_count`, `delivery_times_id`,
			`hits`, `weight_volume_units`, `basic_price_unit_id`, `label_id`, `vendor_id`, `name_en-GB`, `alias_en-GB`, `short_description_en-GB`,
			`description_en-GB`, `meta_title_en-GB`, `meta_description_en-GB`, `meta_keyword_en-GB`, `name_ru-RU`, `alias_ru-RU`, `short_description_ru-RU`, `description_ru-RU`,
			`meta_title_ru-RU`, `meta_description_ru-RU`, `meta_keyword_ru-RU`, `parent_id`, `currency_id`, `access`, `add_price_unit_id`) VALUES (
			'".mysql_real_escape_string($prod_ean)."', 1, 1, '', now(), now(), ".$public.", 1,
			'default', '', 0, 0, $prod_price, $prod_price, 0, 0,
			'awaiting', '', '".mysql_real_escape_string($prod_img)."',$prod_manuf,0,0,0,0,
			0,0,0,0,43,'','','',
			'',	'',	'',	'',	'".mysql_real_escape_string($productName)."','".mysql_real_escape_string($prod_alias)."',	'".mysql_real_escape_string($prod_sdescr)."','".mysql_real_escape_string($prod_descr.$attrtable)."',
			'',	'',	'',0,2,1,0)";
                        
                        
                        $result2 = mysql_query($query1);
			if (!$result2) {
				echo 'ERROR: '.$query1.'<br />';
				echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
				die();
			} else {
				$last_prod_id = mysql_insert_id();
				$new_prods+=1;
				$query2="INSERT INTO `".$jprefix."_jshopping_products_to_categories` SET `product_id`=".mysql_insert_id().", `category_id`=$prod_cat, `product_ordering`=0";
				$result2 = mysql_query($query2);
				if (!$result2) {
					echo 'ERROR: '.$query2.'<br />';
					echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
					die();
				}
                                $i++;
			}
                        
                        
                        
                    }
                }
            }
        }
        print "Добавлено: ".$i;
    }
    
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
    
    
}
