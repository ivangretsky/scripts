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
    
    // Города
    private $mysite;
    
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
        
        нужен массив помни
        
        
        
        
    }
    
    // Конверт
    public function conver($site, $supplier){
        
        // Если нет городов
        if($site==-1){
            $site = array();
            foreach ($this->mysite as $key=>$m) $site[] = $key;
        }

   
        
    foreach($site as $s){    
        $cursite = $this->mysite[$s];
        mysql_select_db($cursite['db']);
        // Вытягиваем запись
        $result = mysql_query("SELECT `order_id`, `order_number`, `order_date`, `order_status`, `order_created`, `order_add_info` FROM `".$this->jprefix."_jshopping_orders` WHERE `order_status`=2 OR `order_status`=6 ORDER BY `order_id` DESC");
        if (!$result) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
            if (mysql_num_rows($result) >0){

                   while($row=mysql_fetch_row($result)) {
                       // Вытягиваем запись о товаре
                       $q = mysql_query("SELECT `product_name`, `product_quantity`, `product_item_price`, `vendor_id` FROM `".$this->jprefix."_jshopping_order_item` WHERE `order_id`=".$row[0]." ");
                       if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
                       
                       
                       
                       
                       
                       while($data=mysql_fetch_row($q)) {
                       
                       
                       //$data = mysql_fetch_row($q);
                       
                       $data[2] = str_replace(".", ",", $data[2]);
                           
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
                        
                        $coment = "";
                        while($comm=mysql_fetch_row($c)) {
                            if($comm[0]!="")
                            $coment .= $comm[0].", ";
                        }
                        
                        $status = "";
                        if($row[3]=="2")
                        $status = "Подтвержден";
                        if($row[3]=="6")
                        $status = "Оплаченн";
                        
                       
                        // Фильтр на поставщика
                        $b = true;
                        if($supplier!=-1)
                        {
                            // Если есть совпадение все норм если нет делаем $b отрицательной
                            $u = false;
                            foreach ($supplier as $s){
                                if($v_name==str_replace("-", "\"", $s)) $u = true;
                              //  print $v_name."==".str_replace("-", "\"", $s)."<br/>";
                            }
                                   
                                if(!$u) $b = false;
                                
                            
                        }
                        
                           
                       if($b)
                       $this->array_csv[] =  array("order_number"=>$row[1], "gorod"=>$cursite['reg'], "name"=>$data[0], "count"=>$data[1], "price"=>$data[2], "sum"=>$data[2]*$data[1], "post"=>$v_name, "data"=>$row[2], "coment"=>$coment, "status"=>$status, "coment_user"=>$row[5]);
                       }
                       
                       
                       
                       
                       
                   }

             }
             
             
            // print_r($this->array_csv);
            }
            /*
            print "<table>";
             print "<th>Номер заказа</th><th>Город</th><th>Имя</th><th>Сколько товаров</th><th>Цена</th><th>Сумма</th><th>Поставщик</th><th>Дата</th><th>Коментарии</th><th>Статус</th><th>Коментарии клиентов</th>";
                foreach ($this->array_csv as $p){
                    print "<tr>";
                        print "<td>".$p["order_number"]."</td>";
                        print "<td>".$p["gorod"]."</td>";
                        print "<td>".$p["name"]."</td>";
                        print "<td>".round($p["count"])."</td>";
                        print "<td>".$p["price"]."</td>";
                        print "<td>".$p["sum"]."</td>";
                        print "<td>".$p["post"]."</td>";
                        print "<td>".$p["data"]."</td>";
                        print "<td>".$p["coment"]."</td>";
                        print "<td>".$p["status"]."</td>";
                        print "<td>".$p["coment_user"]."</td>";
                    print "</tr>";
                }
             print "</table>";
            
            
            */
       }
       
       
       // Сохраняем в файл
       public function saveFile($FileName){
            
           $title_doc = array("Номер заказа", "Город", "Имя", "Сколько товаров", "Цена товара", "Сумма", "Поставщик", "Дата", "Коментарий", "Статус", "Коментарии клиентов");
            $fp = fopen($FileName, 'w');
            fputcsv($fp, $title_doc);
                foreach ($this->array_csv as $p){
                    fputcsv($fp, $p);
                }
            fclose($fp);
         
            
            // Выводим файл в поток
            $string = file_get_contents($FileName);
            Header('Content-Type: application/octet-stream');
            Header('Accept-Ranges: bytes');
            Header('Content-Length: '.strlen($string));
            Header('Content-disposition: attachment; filename="'.$FileName.'"');
            echo $string;
       }
       
       // Выводим города которые будем использовать
       public function printSite(){
           foreach($this->mysite as $key=>$cursite){   
               print "<option value='".$key."'>".$cursite["reg"]."</option>";
           }
       }
       
       
       // Вывести поставщиков
       public function printSupplier(){
           
           // Массив чтобы избавится от дубликатов
           $dupArray = array();
           
           foreach($this->mysite as $cursite){    
             mysql_select_db($cursite['db']);
             
            $result = mysql_query("SELECT `order_id`, `order_number`, `order_date`, `order_status`, `order_created`, `order_add_info` FROM `".$this->jprefix."_jshopping_orders` WHERE `order_status`=2 OR `order_status`=6 ORDER BY `order_id` DESC");
            if (!$result) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
            if (mysql_num_rows($result) >0){

                   while($row=mysql_fetch_row($result)) {
                       // Вытягиваем запись о товаре
                       $q = mysql_query("SELECT `product_name`, `product_quantity`, `product_item_price`, `vendor_id` FROM `".$this->jprefix."_jshopping_order_item` WHERE `order_id`=".$row[0]." ");
                       if (!$q) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
                       while($data=mysql_fetch_row($q)){
                            $v = mysql_query("SELECT `shop_name` FROM `".$this->jprefix."_jshopping_vendors` WHERE `id`=".$data[3]." ");
                            if (!$v) echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
                            $vendor = mysql_fetch_row($v);
                            if($vendor[0]) $dupArray[] = $vendor[0];
                       }
                   }
           }
         }
         
         $dupArray = array_unique($dupArray);
         
         foreach ($dupArray as $key=>$vendor){
             print "<option value='".str_replace("\"", "-", $vendor)."'>".$vendor."</option>";
         }
         
         
         
       }
       
    
}
