<?php
class convertRodsToCat {
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
    // Массив товаров которые обновим
    private $array_products = array();
    // csv данные
    private $csv_data = array();
    // Категории
    
    // Префикс и таблицам
    private $jprefix;
    
    function convertRodsToCat($config){
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
    
    
    // Получаем csv данные
    public function get_csv($FileName){
        
    if (($handle = fopen($FileName, "r")) !== FALSE) {
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            
            if(stripos($data[0], "Спиннинг") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"506");
            if(stripos($data[0], "Компактные") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"507");
            if(stripos($data[0], "Карповые") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"508");
            if(stripos($data[0], "Матчевые") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"509");
            if(stripos($data[0], "Фидерные") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"510");
            if(stripos($data[0], "Телескоп") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"511");
            if(stripos($data[0], "Сёрфовые") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"512");
            if(stripos($data[0], "Лодочные") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"513");
            if(stripos($data[0], "Подсаки") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"514");
            if(stripos($data[0], "Аксессуары") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"515");
            if(stripos($data[0], "Специализированные") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"516");
            if(stripos($data[0], "Форелевые") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"517");
            if(stripos($data[0], "Японские модели") !== FALSE)
                $this->csv_data[] = array("name"=>trim($data[0]), "articul"=>trim($data[1]), "id_cat"=>"518");
            
        }
        
        fclose($handle);
    }else
    {
        die("Не удалось открыть файл: ".$Filecsv);
    }
    
   // print_r($this->csv_data);
    
    }
    
    // Получение всех товаров по категориям
    public function get_products_cat(){
        
        
        $array = array();
        
        $q = mysql_query("SELECT `product_id` FROM `".$this->jprefix."_jshopping_products_to_categories` WHERE `category_id`=204");
        if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        
       // $fp = fopen('../data/file.csv', 'w');
        
        $count = 1936;
        
        while($row=mysql_fetch_row($q)) {
            
        $get_data = mysql_query("SELECT `description_ru-RU` FROM `".$this->jprefix."_jshopping_products` WHERE `product_id`=".$row[0]."");
        if (!$get_data) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        $data_html = mysql_fetch_row($get_data);
        $data_html = str_get_html($data_html[0]);
        $array = array();
            foreach($data_html->find("td") as $key=>$element)
            $array[] = $element->plaintext;
            
            $k = 0;
           foreach ($array as $key=>$td){
                 if(stripos($td, "Артикул") !== FALSE) 
                                    foreach ($this->csv_data as $c){
                                    $k++;
                                  //  print $c["articul"] ."==". trim($array[$key+1])."<br/>";
                                        if(stripos($c["articul"], trim($array[$key+1])) !== FALSE){
                                  //  if($c["articul"] == trim($array[$key+1])){
                                            $this->array_products[] = array("id_db"=>$row[0], "new_cat"=>$c["id_cat"]);
                                            //fputcsv($fp,array("id_db"=>$row[0], "new_cat"=>$c["id_cat"]));
                                          //  break;
                                        }  
                                      //  print $k."<br/>";
                                    }
            }
           
          
                                    $count--;
                                //    print $count."<br/>";
            
        }
      //  fclose($fp);
      //  print_r($this->array_products);
       // print count($this->array_products);
        
        foreach ($this->array_products as $p){
            $sql = mysql_query("UPDATE `vlif9_jshopping_products_to_categories` SET `category_id` = '".$p["new_cat"]."' WHERE `product_id` = ".$p["id_db"]."");
            if (!$sql) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
        }
        
    }

    
}
?>