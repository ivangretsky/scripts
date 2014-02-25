<?php
/*
 * Парсель
 */


class rozetka {
    // Имя базы данных
    private $db    = "tversportru2";
    // Адресс
    private $host = "127.0.0.1";
    // Юзер
    private $user  = "root";
    // Пароль
    private $pass  = "";
    // Объект db
    private $db_link;
    // Объект $xml
    private $xml;
    // Массив групп
    private $array_group = array();
    // Префикс и таблицам
    private $jprefix;
    // Папка с изображениями
    private $folder_images;
    
    function rozetka($config){
        $this->db            = $config["db"];
        $this->host          = $config["host"];
        $this->user          = $config["user"];
        $this->pass          = $config["pass"];
        $this->jprefix       = $config["jprefix"];
        $this->folder_images = $config["folder_images"];
        
        
        $this->db_link = mysql_connect($this->host, $this->user, $this->pass);
        if (!$this->db_link) {
            die ("Не удается подключиться к БД");
        }
        else {
            mysql_query('SET NAMES utf8');
            mysql_select_db($this->db);
        }
    }

    
    public function parselFileXml($FileName, $FileNamePrice){
        
        // Загрузка xml файла
        if (file_exists($FileName)) {
                $xml = simplexml_load_file($FileName);
        } else {
                exit('Не удалось открыть файл '.$FileName);
        }
        
        
        // Загрузка xml файла
        if (file_exists($FileNamePrice)) {
                $xmlprice = simplexml_load_file($FileNamePrice);
        } else {
                exit('Не удалось открыть файл '.$FileNamePrice);
        }
    
        
        // Загоняем ИД и цена в массив
       $_array_price = array();
       foreach ($xmlprice->ПакетПредложений->Предложения[0] as $item) { 
           //print "<td>".(string) $item->Ид[0]."</td>";
           //print "<td>".(string) $item->Наименование[0]."</td>";
           //print "<td>".(string) $item->Цены->Цена->Представление[0]."</td>";
           $_array_price[] = array("id"=>(string)$item->Ид[0], "price"=>(string)$item->Цены->Цена->Представление[0]);
       
        //echo '[', (string) $item->Ид[0], '] ', (string) $item->Наименование[0], "\n";
       }
        

        
       $array_data = array();
       print "<h1>Таблица каталога</h1><table><th>№</th><th>ИД</th><th>Имя</th><th>Цена</th><th>Состояние</th>";
       $i = 0;
       //foreach ($xml->ПакетПредложений->Предложения[0] as $item) { для прайса
       foreach ($xml->Каталог->Товары[0] as $item) {
       $i++;
        print "<tr>";
           print "<td>".$i."</td>";
           print "<td>".(string) $item->Ид[0]."</td>";
           print "<td>".(string) $item->Наименование[0]."</td>";
           
           
           
           
           $b = true;
           foreach ($_array_price as $p){
               if($p["id"]==$item->Ид[0]){
                   // Вытягиваем цену из текста
                   $result = array(); 
                   preg_match_all("/[0-9]+/", $p["price"], $result);
                   print "<td>".implode("",$result[0])."</td>";
                   $b = false;
                   $array_data = array("name"=>$item->Наименование[0],  "price"=>implode("",$result[0]), "");
               }
           }
           
           // Если не было цены ставим ноль
           if($b){
               print "<td>0</td>";
               $array_data = array("name"=>$item->Наименование[0], "price"=>0);
           }
           

           
        // Проверяем на существование товара
        $q = mysql_query("SELECT `id_1c` FROM `".$this->jprefix."_virtuemart_products` WHERE `id_1c`='".mysql_real_escape_string($item->Ид[0])."'");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);

        // Добавляем если нету товара
        $array = mysql_fetch_array($q);
        if ($array["id_1c"]=="")  {
            $this->add_fgphv_virtuemart_products($array_data["name"],$array_data["price"], $item->Группы->Ид, $item->Ид[0]);
            print "<td>Добавлен</td>";
        }else print "<td>Уже есть</td>";
        
        print "</tr>";
        //echo '[', (string) $item->Ид[0], '] ', (string) $item->Наименование[0], "\n";
       }
       print "</table>";
       
       
        
        
    }

    // Создание ссылок
    public function translitIt($str) 
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
    
    
    // Добавляем в fgphv_virtuemart_products_ru_ru
    public function add_fgphv_virtuemart_products_ru_ru($id, $name, $price, $idg){
        
        
        
        // Создаем категорию
        $q = mysql_query("SELECT MAX(`virtuemart_product_id`) FROM `".$this->jprefix."_virtuemart_products_ru_ru`");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);

        $max_count_id = mysql_fetch_array($q);
        $q=mysql_query("INSERT INTO `".$this->jprefix."_virtuemart_products_ru_ru` (`virtuemart_product_id` ,`product_s_desc` ,`product_desc` ,`product_name` ,`metadesc` ,`metakey` ,`customtitle` ,`slug`)
        VALUES ('".mysql_real_escape_string($id)."',  '',  '',  '".mysql_real_escape_string($name)."',  '',  '',  '',  '".mysql_real_escape_string($this->translitIt($name)).'-'.mt_rand(100, 9999)."');");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);

        $this->add_fgphv_virtuemart_product_prices(($id), $price, $idg);
    }
    
    // Добавляем в fgphv_virtuemart_product_prices
    public function add_fgphv_virtuemart_product_prices($id, $price, $idg){
        $q = mysql_query("INSERT INTO `".$this->jprefix."_virtuemart_product_prices` (
        `virtuemart_product_price_id` ,
        `virtuemart_product_id` ,
        `virtuemart_shoppergroup_id` ,
        `product_price` ,
        `override` ,
        `product_override_price` ,
        `product_tax_id` ,
        `product_discount_id` ,
        `product_currency` ,
        `product_price_publish_up` ,
        `product_price_publish_down` ,
        `price_quantity_start` ,
        `price_quantity_end` ,
        `created_on` ,
        `created_by` ,
        `modified_on` ,
        `modified_by` ,
        `locked_on` ,
        `locked_by`
        )
        VALUES (
        NULL ,  '".mysql_real_escape_string($id)."',  '0',  '".mysql_real_escape_string($price)."',  '0',  '0.00000',  '0',  '0',  '131',  '0000-00-00 00:00:00',  '0000-00-00 00:00:00',  '0',  '0', 'now()',  '422',  'now()',  '423',  '0000-00-00 00:00:00',  '0'
        );");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        
        $this->add_fgphv_virtuemart_product_categories($id, $idg);
        
    }
    
    // Добавляем в fgphv_virtuemart_product_prices
    public function add_fgphv_virtuemart_products($name, $price, $idg, $id){
        
     // Если не коректная цена то не публикуем  
     $p = 1;
     if($price==0)$p = 0;
     // Создаем основную категорию   
     $q = mysql_query("INSERT INTO `".$this->jprefix."_virtuemart_products` (
        `virtuemart_product_id` ,
        `virtuemart_vendor_id` ,
        `product_parent_id` ,
        `product_sku` ,
        `product_weight` ,
        `product_weight_uom` ,
        `product_length` ,
        `product_width` ,
        `product_height` ,
        `product_lwh_uom` ,
        `product_url` ,
        `product_in_stock` ,
        `product_ordered` ,
        `low_stock_notification` ,
        `product_available_date` ,
        `product_availability` ,
        `product_special` ,
        `product_sales` ,
        `product_unit` ,
        `product_packaging` ,
        `product_params` ,
        `hits` ,
        `intnotes` ,
        `metarobot` ,
        `metaauthor` ,
        `layout` ,
        `published` ,
        `pordering` ,
        `created_on` ,
        `created_by` ,
        `modified_on` ,
        `modified_by` ,
        `locked_on` ,
        `locked_by`,
        `id_1c`
        )
        VALUES (
        NULL ,  '1',
        '0',  '',
        NULL ,  'KG',
        NULL , NULL ,
        NULL ,  'M',  '',
        '0',  '0',  '0',
        'now()',  '',  '0',  '0',
        'KG', NULL ,
        'min_order_level=\"\"|max_order_level=\"\"|step_order_level=\"\"|product_box=\"\"|', NULL ,  '',  '',  '',  '0',  '".mysql_real_escape_string($p)."',  '0',  'now()', '422',  'now()',  '423',  '0000-00-00 00:00:00',  '0', ''
        );");
     
     
     if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
     $insert = mysql_insert_id();
     
     // Создаем RU категорию
     $this->add_fgphv_virtuemart_products_ru_ru($insert,$name, $price, $idg);
  
     // Создаем строку в fgphv_c_1c
     $q = mysql_query("UPDATE `".$this->jprefix."_virtuemart_products` SET `id_1c` = '".mysql_real_escape_string($id)."' WHERE `virtuemart_product_id` = ".mysql_real_escape_string($insert).";");
     if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
     
    }
  
    // Добавляем в fgphv_virtuemart_product_prices
    public function add_fgphv_virtuemart_product_categories($id, $idg){
        $cat = 0;
        foreach ($this->array_group as $key=>&$q){
            if($q["id"]==$idg)$cat = $q["cid"];
        }
        $q = mysql_query("INSERT INTO `".$this->jprefix."_virtuemart_product_categories`
            (`id` ,`virtuemart_product_id` ,`virtuemart_category_id` ,`ordering`)
              VALUES (NULL ,  '".mysql_real_escape_string($id)."',  '".mysql_real_escape_string($cat)."',  '0');");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
    }
    
   
    
    public function recFunction($g, $id=0, $link = null){
        // 1 Вложение
        foreach ($g->Группа as $item){
                $this->array_group[] = array("name"=>((string)$item->Наименование), "id"=>(string)$item->Ид, "cat"=>0, "cid"=>$this->fgphv_virtuemart_categories((string)$item->Ид), "ciddown"=>0); 
                // 2 Вложение
                $ciddown = $this->array_group[count($this->array_group)-1]["cid"];
                if(count($item->Группы)>0)
                foreach ($item->Группы->Группа as $item2){
                        $this->array_group[] = array("name"=>((string)$item2->Наименование), "id"=>(string)$item2->Ид, "cat"=>1, "cid"=>$this->fgphv_virtuemart_categories((string)$item2->Ид), "ciddown"=>$ciddown);           
                        // 3 Вложение
                        $ciddown1 = $this->array_group[count($this->array_group)-1]["cid"];
                        if(count($item2->Группы)>0)
                        foreach ($item2->Группы->Группа as $item3){
                                $this->array_group[] = array("name"=>((string)$item3->Наименование), "id"=>(string)$item3->Ид, "cat"=>2, "cid"=>$this->fgphv_virtuemart_categories((string)$item3->Ид), "ciddown"=>$ciddown1); 
                                // 4 Вложение
                                $ciddown2 = $this->array_group[count($this->array_group)-1]["cid"];
                                if(count($item3->Группы)>0)
                                foreach ($item3->Группы->Группа as $item4){
                                        $this->array_group[] = array("name"=>((string)$item4->Наименование), "id"=>(string)$item4->Ид, "cat"=>3, "cid"=>$this->fgphv_virtuemart_categories((string)$item4->Ид), "ciddown"=>$ciddown2);           
                                        // 5 Вложение
                                        $ciddown3 = $this->array_group[count($this->array_group)-1]["cid"];
                                        if(count($item4->Группы)>0)
                                        foreach ($item4->Группы->Группа as $item5){
                                                $this->array_group[] = array("name"=>((string)$item5->Наименование), "id"=>(string)$item5->Ид, "cat"=>4, "cid"=>$this->fgphv_virtuemart_categories((string)$item5->Ид), "ciddown"=>$ciddown3);    
                                                // 6 Вложение
                                                $ciddown4 = $this->array_group[count($this->array_group)-1]["cid"];
                                                if(count($item5->Группы)>0)
                                                foreach ($item5->Группы->Группа as $item6){
                                                        $this->array_group[] = array("name"=>((string)$item6->Наименование), "id"=>(string)$item6->Ид, "cat"=>5, "cid"=>$this->fgphv_virtuemart_categories((string)$item6->Ид), "ciddown"=>$ciddown4);           
                                                }
                                        }
                                }
                        }
                }
                
        }
        
    }
    
    // Функция парсинга группы
    public function parseGroup($FileName){
        // Загрузка xml файла
        if (file_exists($FileName)) {
                $xml = simplexml_load_file($FileName);
        } else {
                exit('Не удалось открыть файл '.$FileName);
        }

         $this->recFunction($xml->Классификатор->Группы->Группа->Группы);
         
         print "<ul>";
         $i = 0;
         foreach ($this->array_group as $key=>$q){
                   if($i==$q["cat"]){
                   print "<li>".$q["cat"]."-".$q["name"]." - ".($q["cid"]==-1?"Уже есть":"Добавлен")."</li>";}
                   
                   if($i<$q["cat"]){
                    print "<ul><li>".$q["cat"]."-".$q["name"]." - ".($q["cid"]==-1?"Уже есть":"Добавлен")."</li>";
                    $i = $q["cat"];
                   }
                   
                   if($i>$q["cat"]){
                      
                    for ($k = 1; $k <= ($i - $q["cat"]); $k++)print "</ul>";
                    print "<li>".$q["cat"]."-".$q["name"]." - ".($q["cid"]==-1?"Уже есть":"Добавлен")."</li>";
                    $i = $q["cat"];
                   }
                   
         }
      print "</ul>";
        
    }
    
    
    
    // Создание категории с иерархией
    public function fgphv_virtuemart_category_categories($i1, $i2){
        $q = mysql_query("INSERT INTO `".$this->jprefix."_virtuemart_category_categories` (`id`, `category_parent_id`, `category_child_id`, `ordering`) VALUES (NULL, '".mysql_real_escape_string($i1)."', '".mysql_real_escape_string($i2)."', '0');");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
    } 
    
    // Создание категории в ru ru таблице
    public function fgphv_virtuemart_categories_ru_ru($id, $name){
        $q = mysql_query("INSERT INTO `".$this->jprefix."_virtuemart_categories_ru_ru` (`virtuemart_category_id`, `category_name`, `category_description`, `metadesc`, `metakey`, `customtitle`, `slug`) VALUES ('".mysql_real_escape_string($id)."', '".mysql_real_escape_string($name)."', '', '', '', '', '".mysql_real_escape_string($this->translitIt($name)).'-'.mt_rand(100, 9999)."');");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
    }
    
    // Создание основной категории
    public function fgphv_virtuemart_categories($ids){
        
        // Проверяем на существование категории
        $q = mysql_query("SELECT `id_1c` FROM `".$this->jprefix."_virtuemart_categories` WHERE `id_1c`='".mysql_real_escape_string($ids)."'");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);

        // Добавляем если нету категории
        $array = mysql_fetch_array($q);
        if ($array["id_1c"]!="") return -1;
        
        
        
        
        // Создаем категорию
        $q = mysql_query("INSERT INTO `".$this->jprefix."_virtuemart_categories` (`virtuemart_category_id`, `virtuemart_vendor_id`, `category_template`, `category_layout`, `category_product_layout`, `products_per_row`, `limit_list_step`, `limit_list_initial`, `hits`, `metarobot`, `metaauthor`, `ordering`, `shared`, `published`, `created_on`, `created_by`, `modified_on`, `modified_by`, `locked_on`, `locked_by`) VALUES (NULL, '1', '0', '0', '0', '0', '0', '0', '0', '', '', '1', '0', '1', 'now()', '422', 'now()', '422', '0000-00-00 00:00:00', '0');");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        
        $insert = mysql_insert_id();
        // Создаем строку в fgphv_g_1c
        $q = mysql_query("UPDATE `".$this->jprefix."_virtuemart_categories` SET `id_1c` = '".mysql_real_escape_string($ids)."' WHERE `virtuemart_category_id` = ".mysql_real_escape_string($insert).";");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        
        return $insert;
    }
    
    // Создаем категории
  /*  public function create_categories(){
         $i = 0;
         $insert_id = 0;
         $insert_id_down = 0;
         foreach ($this->array_group as $key=>&$q){
                   if($i==$q["cat"]){
                     $insert_id_down = $insert_id;
                     $insert_id = $this->fgphv_virtuemart_categories();
                     
                     $this->fgphv_virtuemart_categories_ru_ru($insert_id, $q["name"]);
                     $this->fgphv_virtuemart_category_categories($q["cid"],$insert_id);
                     $q["cid"] = $insert_id;
                   }
                   
                   if($i<$q["cat"]){
                    $i = $q["cat"];
                    $insert_id_down = $insert_id;
                    $insert_id = $this->fgphv_virtuemart_categories();
                    
                    $this->fgphv_virtuemart_categories_ru_ru($insert_id, $q["name"]);
                    $this->fgphv_virtuemart_category_categories($q["cid"],$insert_id);
                    $q["cid"] = $insert_id;
                   }
                   
                   if($i>$q["cat"]){
                    $insert_id_down = $insert_id;  
                    for ($k = 1; $k <= ($i - $q["cat"]); $k++){print "</ul>";$insert_id_down--;};
                    $i = $q["cat"];
                    $insert_id = $this->fgphv_virtuemart_categories();
                    
                    $this->fgphv_virtuemart_categories_ru_ru($insert_id, $q["name"]);
                    $this->fgphv_virtuemart_category_categories($q["cid"],$insert_id);
                    $q["cid"] = $insert_id;
                   }
              // exit();    
         }
    }*/
    
    
    public function create_categories(){
        $i = 0;
        $gr = 0;
        $array = array();
        //print_r($this->array_group);
        // Создаем сначало категории без иерархии
        foreach ($this->array_group as $key=>&$q)if($q["cid"]!=-1){
           // $q["cid"] = $this->fgphv_virtuemart_categories();
            $array[$q["cat"]] = $q["cid"];
            $this->fgphv_virtuemart_categories_ru_ru($q["cid"], $q["name"]);
            $this->fgphv_virtuemart_category_categories($q["ciddown"],$q["cid"]);
        }
        
        // Создаем группу с иерархией
        if(false)foreach ($this->array_group as $key=>&$q){
            
                   if($i==$q["cat"]){
                       $i = $q["cat"];
                       $this->fgphv_virtuemart_category_categories($q["cid"],$q["cid"]);
                      // $gr = $q["cid"];
                   }
                   
                   if($i<$q["cat"]){
                       $i = $q["cat"];
                       $this->fgphv_virtuemart_category_categories($q["cid"],$q["cid"]);
                       $gr = $q["cid"];
                   }
                   
                   if($i>$q["cat"]){
                       $i = $q["cat"];
                       $this->fgphv_virtuemart_category_categories($q["cid"],$q["cid"]);
                       $gr = $q["cid"];
                   }
        }
        
        
        
        
        
    }
    
    
    // Добавить изображение в товар
    public function add_image_product($name, $id){
        $format = "";
        if(stripos($name, "jpg") !== FALSE)
         $format = "image/jpeg";
        elseif(stripos($name, "png") !== FALSE)
         $format = "image/png";
        elseif(stripos($name, "gif") !== FALSE)
         $format = "image/gif";
        elseif(stripos($name, "bmp") !== FALSE)
         $format = "image/bmp";
        
        
        // Проверяем на существование файла в базе
        $q = mysql_query("SELECT `virtuemart_media_id` FROM `".$this->jprefix."_virtuemart_medias` WHERE `file_url` = 'images/stories/virtuemart/product/".mysql_real_escape_string($name)."'");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);

        // Добавляем если нету категории
        $array = mysql_fetch_array($q);
        if ($array["virtuemart_media_id"]!="") return -1;
        
        
        
        // Создаем запись в базе данных
        $q = mysql_query("INSERT INTO `".$this->jprefix."_virtuemart_medias` (`virtuemart_media_id`, `virtuemart_vendor_id`, `file_title`, `file_description`, `file_meta`, `file_mimetype`, `file_type`, `file_url`, `file_url_thumb`, `file_is_product_image`, `file_is_downloadable`, `file_is_forSale`, `file_params`, `file_lang`, `shared`, `published`, `created_on`, `created_by`, `modified_on`, `modified_by`, `locked_on`, `locked_by`) VALUES (NULL, '1', '".mysql_real_escape_string($name)."', '', '', '".mysql_real_escape_string($format)."', 'product', 'images/stories/virtuemart/product/".mysql_real_escape_string($name)."', '', '0', '0', '0', '', '', '0', '1', 'now()', '423', 'now()', '423', '0000-00-00 00:00:00', '0');");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);   
        $insert = mysql_insert_id();
        
        
        
        
        $q = mysql_query("INSERT INTO `".$this->jprefix."_virtuemart_product_medias` (`id`, `virtuemart_product_id`, `virtuemart_media_id`, `ordering`) VALUES (NULL, '".mysql_real_escape_string($id)."', '".mysql_real_escape_string($insert)."', '1');");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        return true;
    }
    
    // Импорт изображений в продукты
    public function import_images_products($size, $quality, $export){
        // Получаем все продукты с id_1c
        $q = mysql_query("SELECT `virtuemart_product_id`, `id_1c` FROM `".$this->jprefix."_virtuemart_products`");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        
        
      
           $array_file = array();
           
           // Читаем каталог в массив
           $handle = opendir($this->folder_images);
            if($handle)
             while(true == ($dir = readdir($handle)))
              if($dir!='.' && $dir!='..')
               $array_file[] = $dir;
                
                
                
                while($row=mysql_fetch_row($q))
                 foreach ($array_file as $f)
                  if(stripos($f, $row[1]) !== FALSE){
                   echo $f.'<br/>';
                    if($this->add_image_product($f, $row[0]))
                          $this->ResizeImage($this->folder_images.$f, $size, $quality, $export, $f );
                  }
        
    }
    
    
    
    
    function ResizeImage ($filename, $size = 300, $quality = 85, $path_save, $new_filename)
    {
        /*
        * Адрес директории для сохранения картинки
        */
        $dir  = $path_save;
        $dir = str_replace ("\\", "/", $path_save);
        /*
        * Извлекаем формат изображения, то есть получаем 
        * символы находящиеся после последней точки
        */
        $ext  = strtolower(strrchr(basename($filename), "."));
        /*
        * Допустимые форматы
        */
        $extentions = array('.jpg', '.gif', '.png', '.bmp');
    
        if (in_array($ext, $extentions)) {   
             $percent = $size; // Ширина изображения миниатюры
        
             list($width, $height) = getimagesize($filename); // Возвращает ширину и высоту
             
             
             $newwidth = $height;
             $newheight    = $height * $percent;
             if($percent<$width) $newwidth     = $newheight / $width;
             
             if($percent<$width)
                 $thumb = imagecreatetruecolor($percent, $newwidth);
             else
                 $thumb = imagecreatetruecolor($percent, $height);
             
             imagefill($thumb, 0, 0, 0xFFFFFF);
        
             switch ($ext) {
                 case '.jpg':
                     $source = @imagecreatefromjpeg($filename);
                     break;
                
                  case '.gif':
                     $source = @imagecreatefromgif($filename);
                     break;
                
                  case '.png':
                     $source = @imagecreatefrompng($filename);
                     break;
                
                  case '.bmp':
                      $source = @imagecreatefromwbmp($filename);
              }
    
            /*
            * Функция наложения, копирования изображения
            */
            if($percent<$width)
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $percent, $newwidth, $width, $height);
            else
                imagecopyresized($thumb, $source, round(($percent-$width)/2), 0, 0, 0, $width, $height, $width, $height);
        
            /*
            * Создаем изображение
            */
            switch ($ext) {
                case '.jpg':
                    imagejpeg($thumb, $dir . $new_filename, $quality);
                    break;
                    
                case '.gif':
                    imagegif($thumb, $dir . $new_filename);
                    break;
                    
                case '.png':
                    imagepng($thumb, $dir . $new_filename, round($quality/10));
                    break;
                    
                case '.bmp':
                    imagewbmp($thumb, $dir . $new_filename);
                    break;
            }    
            } else {
                return false;
            }
    
    /* 
    *  Очищаем оперативную память сервера от временных файлов, 
    *  которые потребовались для создания миниатюры
    */
        @imagedestroy($thumb);         
        @imagedestroy($source);  
            
        return true;
    }
    
    
    // Проверка базы данных
    public function check_db(){
        
        $q = mysql_query("SELECT `id_1c` FROM `".$this->jprefix."_virtuemart_products` WHERE 0");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        if (!$q) {
           // Создаем поле
            $q = mysql_query("ALTER TABLE `".$this->jprefix."_virtuemart_products` ADD `id_1c` TEXT NOT NULL;");
            if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
            print "<br/>Созданно поле id_1c в ".$this->jprefix."_virtuemart_products";
        }
        
        $q = mysql_query("SELECT `id_1c` FROM `".$this->jprefix."_virtuemart_categories` WHERE 0");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        if (!$q) {
           // Создаем поле
            $q = mysql_query("ALTER TABLE `".$this->jprefix."_virtuemart_categories` ADD `id_1c` TEXT NOT NULL;");
            if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
            print "<br/>Созданно поле id_1c в ".$this->jprefix."_virtuemart_categories";
        }
        
    }
    
}


?>