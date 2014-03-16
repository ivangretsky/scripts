<?php
class ClassConvertTable {
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
    // Массив товаров которые надо спарсить
    private $array_products = array();
    // Префикс и таблицам
    private $jprefix;
    
    function ClassConvertTable($config){
        $this->db            = $config["db"];
        $this->host          = $config["host"];
        $this->user          = $config["user"];
        $this->pass          = $config["pass"];
        $this->jprefix       = $config["jprefix"];
        
        
        $this->db_link = mysql_connect($this->host, $this->user, $this->pass);
        if (!$this->db_link) {
            die ("Не удается подключиться к БД");
        }
        else {
            mysql_query('SET NAMES utf8');
            mysql_select_db($this->db);
        }
    }

    
    // Парсим и заливаем характеристики товара
    public function parcerTableToCh(){
        $q = mysql_query("SELECT `product_id` FROM `".$this->jprefix."_jshopping_products_to_categories` WHERE `category_id`=388");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        
        
        while($row=mysql_fetch_row($q)) {
           
        $get_data = mysql_query("SELECT `description_ru-RU` FROM `".$this->jprefix."_jshopping_products` WHERE `product_id`=".$row[0]."");
        if (!$get_data) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        
        $data_html = mysql_fetch_row($get_data);
        $data_html = str_get_html($data_html[0]);
        $array = array();
        // Вес в грам 18
        $massa_g = 0;
        // Длина в см 19
        $length_cm = 0;
        // Мин заглубление 20
        $min_z = 0;
        // Макс заглубление 21
        $max_z = 0;
        // Тип 22
        $type_t = "";
        // Цвет 23
        $color_t = "";
        
        
        foreach($data_html->find("td") as $key=>$element)
            $array[] = $element->plaintext;
        
        foreach ($array as $key=>$td){
            
            // Вес в грам 18
            if(stripos($td, "Вес") !== FALSE) $massa_g = preg_replace("/[^0-9]/", '', trim($array[$key+1]));
            // Длина в см 19
            if(stripos($td, "Длина") !== FALSE) $length_cm = preg_replace("/[^0-9]/", '', trim($array[$key+1]));
            // Тип 22
            if(stripos($td, "суспендер") !== FALSE) $type_t = "12";
            if(stripos($td, "плавающий") !== FALSE) $type_t = "13";
            if(stripos($td, "медленно тонущий") !== FALSE) $type_t = "14";
            
            if(stripos($td, "Заглубление") !== FALSE&&trim($array[$key+1])!=="переменнаям."){
                
                
             /*if(stripos(trim($array[$key+1]), "-") !== FALSE){
                $result = array(); 
                preg_match_all("/[^0-9]+/", trim($array[$key+1]), $result);
                //foreach($result as $n){print $n[0]."-".$n[1];}
                print $result[0][0]."-".$result[0][1]."=".trim($array[$key+1])."<br/>";
             }*/
             $str = str_replace(".", ",", trim($array[$key+1]));
             $str = str_replace("–", "-", $str);
             
             
             $array_z = explode("-", $str);
            // print $str."<br/>";
             // fix с запятой
             if(count(explode(",", trim($array[$key+1])))>0)
             if(count($array_z)>1){
                // print "1=".trim($array[$key+1])."<br/>";
                 $min_z = preg_replace("/[^0-9,0-9]/", '', trim($array_z[0]));
                 $max_z = preg_replace("/[^0-9,0-9]/", '', trim($array_z[1]));
                 
                 if(substr($max_z, strlen($max_z)-1, strlen($max_z)-1)==",")
                 $max_z = substr($max_z, 0, strlen($max_z)-1);
             }
             else{
                 $min_z = 0;
                 $max_z = preg_replace("/[^0-9,0-9]/", '', trim($array[$key+1]));
                 
                 if(substr($max_z, strlen($max_z)-1, strlen($max_z)-1)==",")
                 $max_z = substr($max_z, 0, strlen($max_z)-1);
                 
             }
          // print " LEN ".substr($max_z, strlen($max_z)-1, strlen($max_z)-1);
               
            // print "Мин-".$min_z." Макс-".$max_z."=".trim($array[$key+1])."<br/>";
             
              //  print preg_replace("/[^0-9,0-9]/", '', trim($array[$key+1]))."=".trim($array[$key+1])."<br/>";
             
            }
            
            
            // Цвет 23
            if(stripos($td, "CG") !== FALSE) $color_t = "15";
            if(stripos($td, "FB") !== FALSE) $color_t = "16";
            if(stripos($td, "FMBL") !== FALSE) $color_t = "17";
            if(stripos($td, "FGGM") !== FALSE) $color_t = "18";
            if(stripos($td, "GGH") !== FALSE) $color_t = "19";
            if(stripos($td, "GGH") !== FALSE) $color_t = "20";
        }
        
        //continue;
        
        $q_update = mysql_query("UPDATE `".$this->jprefix."_jshopping_products` SET "
                . "`extra_field_18` = '".$massa_g."', "
                . "`extra_field_19` = '".$length_cm."', "
                . "`extra_field_20` = '".$min_z."', "
                . "`extra_field_21` = '".$max_z."', "
                . "`extra_field_22` = '".$type_t."', "
                . "`extra_field_23` = '".$color_t."' "
                . " WHERE `product_id` =".$row[0]."");
        if (!$q_update) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        
        }
        print "ВСЕ";
    }
    
}
?>