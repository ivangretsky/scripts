<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of convertOrdertsToCsv
 *
 * @author admin
 */
class convertOrdertsToCsv {
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
    // Массив который будем писать в файл
    private $array_csv = array();
    // csv данные
    private $csv_data = array();
    // Категории
    
    // Префикс и таблицам
    private $jprefix;
    
    function convertOrdertsToCsv($config){
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
    
    // Конверт
    public function conver(){
        // Вытягиваем запись
        $result = mysql_query("SELECT `order_id`,`order_number`,`order_date`,`order_status`,`order_created` FROM `".$this->jprefix."_jshopping_orders` WHERE `vendor_id`=7 ORDER BY `order_id` DESC LIMIT 100");
        if (!$result) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
            if (mysql_num_rows($result) >0){

                   while($row=mysql_fetch_row($result)) {
                       // Вытягиваем запись о товаре
                       $q = mysql_query("SELECT `product_name`, `product_quantity`, `product_item_price`, `vendor_id` FROM `".$this->jprefix."_jshopping_order_item` WHERE `order_id`=".$row[0]." ");
                       if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
                       $data = mysql_fetch_row($q);
                       
                       // Узнать поставщика
                       $v_name = "net";
                       if($data[3]!="0"){
                        $v = mysql_query("SELECT `shop_name` FROM `".$this->jprefix."_jshopping_vendors` WHERE `id`=".$data[3]." ");
                        if (!$v) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
                        $vendor = mysql_fetch_row($v);
                        $v_name = $vendor[0];
                       }
                       
                       // Узнать коментарии
                       $c_name = "net";
                       
                       
                       
                        $c = mysql_query("SELECT `comments` FROM `".$this->jprefix."_jshopping_order_history` WHERE `order_id`=".$row[0]." ");
                        if (!$c) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
                        $coment = mysql_fetch_row($c);
                        

                       
                       $this->array_csv[] =  array("order_number"=>$row[1], "name"=>$data[0], "count"=>$data[1], "price"=>$data[2], "sum"=>$data[2]*$data[1], "post"=>$v_name, "data"=>$row[2], "coment"=>$coment[0]);
                   }

             }
             
             print "<table>";
             print "<th>Номер заказа</th><th>Имя</th><th>Сколько товаров</th><th>Цена</th><th>Сумма</th><th>Поставщик</th><th>Дата</th><th>Коментарии</th>";
                foreach ($this->array_csv as $p){
                    print "<tr>";
                        print "<td>".$p["order_number"]."</td>";
                        print "<td>".$p["name"]."</td>";
                        print "<td>".round($p["count"])."</td>";
                        print "<td>".$p["price"]."</td>";
                        print "<td>".$p["sum"]."</td>";
                        print "<td>".$p["post"]."</td>";
                        print "<td>".$p["data"]."</td>";
                        print "<td>".$p["coment"]."</td>";
                    print "</tr>";
                }
             print "</table>";
            // print_r($this->array_csv);
             
       }
       
       
       // Сохраняем в файл
       public function saveFile($FileName){
           $title_doc = array("Номер заказа", "Имя", "Сколько товаров", "Цена товара", "Сумма", "Поставщик");
            $fp = fopen($FileName, 'w');
            fputcsv($fp, $title_doc);
                foreach ($this->array_csv as $p){
                    fputcsv($fp, $p);
                }
            fclose($fp);
       }
    
}
