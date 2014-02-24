<?php
include_once '../class/url.parsel.dxlab.class.php';
/*
 * Парсель
 */


class shimano {
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
    // Объект $xml
    private $xml;
    
    function shimano($config){
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
    
    // Парсим ссылки из файла
    public function parselXmlUrl($FileXml){
        if (file_exists($FileXml)) {
            $this->xml = simplexml_load_file($FileXml);
        } else {
            die ('Не удалось открыть файл '.$FileXml);
        }
        
        $i = 0;
        foreach($this->xml->url as $url) {
            if(stripos($url->loc, "/models/details/") !== FALSE){
                $i++;
                print $i." - ".$url->loc."<br/>";
                $this->pushUrlToDB($url->loc);
            }
        }
        
    }
    
    // Добавляем информацию в базу данных
    public function pushUrlToDB($url){
        $result = mysql_query("INSERT INTO  `".$this->table."` (`url`, `status`) VALUES ('".$url."',  '0')");
        if (!$result) {die (mysql_errno($this->db_link) . ": " . mysql_error($this->db_link));}
    }
    
    // Спарсить сайт в базу
    public function parselUrlToDB($_table){
        // Не обработтаные ссылки
        $result = mysql_query("SELECT * FROM `".$this->table."` WHERE `status`=0");
        // Обработанные ссылки
        $i = mysql_num_rows(mysql_query("SELECT * FROM `".$this->table."` WHERE `status`=1"));
        // Общее колличество ссылок
        $count = mysql_num_rows(mysql_query("SELECT * FROM `".$this->table."`"));
        file_put_contents("../normak/json.php", '{"max":'.$count.',"to":"'.$i.'"}');
        if (!$result) {die (mysql_errno($this->db_link) . ": " . mysql_error($this->db_link));}
        if (mysql_num_rows($result) >0){
	    while($row=mysql_fetch_row($result)) {
                $i++;
                
                // Проверка на доступность страницы
                $header = get_headers($row[1],0);
                if($header[0]=="HTTP/1.1 404 Not Found") continue;
                
                $urlParsel = new urlParsel( "http://shimano.ru/", $row[1]);
                $re_array =  array( "h1"=>$urlParsel->getText ("h1"),
                             "price"=>$urlParsel->getText      (".price"),
                             "imgT"=>$urlParsel->getImg        ("a.highslide img"),
                             "param"=>$urlParsel->getText      (".params"),
                             "model_desc"=>$urlParsel->getText (".model_desc"));
               
                
                $this->pushSiteToDB($row[1], "siteinfo", $re_array);
        
               
                $update = mysql_query("UPDATE `".$this->table."` SET `status` = '1' WHERE `id` =".$row[0]);
                if (!$update) {die (mysql_errno($this->db_link) . ": " . mysql_error($this->db_link));}
                file_put_contents("../normak/json.php", '{"max":'.$count.',"to":"'.$i.'"}');
            } 
         }
    }
    
    // Добавить сайт в базу
    public function pushSiteToDB($url, $_table = "siteinfo", $re_array){
        $query = "INSERT INTO  `".$_table."` (`url`, `title`, `price`, `img`, `param`, `desc`, `status`) VALUES ('".str_replace("'","",$url)."', '".mysql_real_escape_string($re_array["h1"][0])."', '".mysql_real_escape_string($re_array["price"][0])."', '".mysql_real_escape_string($re_array["imgT"][0])."', '".mysql_real_escape_string(implode("&", $re_array["param"]))."', '".mysql_real_escape_string($re_array["model_desc"][0])."', '0')";
        file_put_contents("../normak/data_query.php", $query);
        $result = mysql_query($query);
        if (!$result) {die (mysql_errno($db) . ": " . mysql_error($db));}
    }
    
    
}


?>