<?php
/* Управление пользователями
 * 
 */

class usermanager {
    
    // Имя базы данных
    private $db    = "tversportru2";
    // Адресс
    private $adress = "127.0.0.1";
    // Юзер
    private $user  = "root";
    // Пароль
    private $pass  = "";
    // Таблица
    private $table = "`groupvk`";
    // Объект db
    private $db_link;
    // добавленые юзеры
    public $user_add = array();
    
    // Конструктор
    function usermanager(){
        $this->db_link = mysql_connect($this->adress, $this->user, $this->pass);
        if (!$this->db_link) {
            die ("Не удается подключиться к БД");
        }
        else {
            mysql_query('SET NAMES utf8');
            mysql_select_db($this->db);
        }
    }
    
    
    // Выводим новых пользователей или обновленных
    // $r - render
    public function render_table_array(&$r){
        if(count($this->user_add)!=0){
            $r->render_table_array("Новые или обновленные пользователи", $this->user_add, array("idvk"=>"idvk", "name"=>"Имя", "surname"=>"Фамилия", "status"=>"Статус", "foto"=>"Фото", "operation"=>"Операция" ));
        }
    }
    
    // Показываем ошибки юзеров
    public function showerror(){
        echo mysql_errno($this->db_link) . ": " . mysql_error($this->db_link);
    }
    
    
    // Добавить юзера
    public function adduser($idvk, $name, $surname, $status, $foto ){
       $query =  "INSERT INTO  ".$this->table." (`idvk`, `name`, `surname`, `status`, `foto`)VALUES ('".$idvk."',  '".$name."',  '".$surname."',  '".$status."',  '".$foto."')";
       $this->user_add[] = array("idvk"=>$idvk, "name"=>$name, "surname"=>$surname, "status"=>$status, "foto"=>$foto, "operation"=>"Добавление");
      
         if (!mysql_query($query)) 
          $this->showerror ();
    }
    
    
    // Добавить юзеров
    public function addusers($data){
        
        
        // Получаем весь список из базы данных
        $query = "SELECT * FROM ".$this->table."";
        $data_table = mysql_query($query);
        if (!$data_table) 
          $this->showerror ();
        
        // Заполняем массив данными из базы
        $_data_users = array();
        while($row=mysql_fetch_object($data_table)){
            $_data_users[$row->{"idvk"}] = array("idvk"=>$row->{"idvk"}, "name"=>$row->{"name"}, "surname"=>$row->{"surname"}, "status"=>$row->{"status"}, "foto"=>$row->{"foto"} );
        }
    
        $vk_data_users_id = array();
        foreach ($data as $inf){
            $vk_data_users_id[$inf["uid"]] = array("uid"=>$inf["uid"], "first_name"=>$inf["first_name"], "last_name"=>$inf["last_name"], "foto"=>$inf["foto"], "photo_50"=>$inf["photo_50"] );
        }
    
          
          foreach ($vk_data_users_id as $row)   {
          $cont = false;
            // Сравниваем списки из контака и из базы данных, если есть юзер то не добавляем
            if(!empty($_data_users[$row["uid"]])){ 
              $cont = true;
            }
        
          if($cont) continue;
    

            $foto = "";
            if($inf["photo_50"]!="") $foto = $row["photo_50"];                  
                $this->adduser($row["uid"], $row["first_name"], $row["last_name"], "0", $foto); 
            
        }
        
        
        
}
    



    // Удалить юзера
    public function deleteuser($idvk, $name, $surname, $status, $foto){
       $query =  "DELETE FROM  ".$this->table." WHERE  `idvk` = ".$idvk; 
       $this->user_add[] = array("idvk"=>$idvk, "name"=>$name, "surname"=>$surname, "status"=>$status, "foto"=>$foto, "operation"=>"Удален");
        if (!mysql_query($query)) 
         $this->showerror ();
    }
    
    
    // Удалить юзеров
    public function deleteusers($data){
           
        // Получаем весь список из базы данных
        $query = "SELECT * FROM ".$this->table."";
        $data_table = mysql_query($query);
        if (!$data_table) 
          $this->showerror ();
        
        // Заполняем массив данными из базы
        $_data_users = array();
        while($row=mysql_fetch_object($data_table)){
            $_data_users[$row->{"idvk"}] = array("idvk"=>$row->{"idvk"}, "name"=>$row->{"name"}, "surname"=>$row->{"surname"}, "status"=>$row->{"status"}, "foto"=>$row->{"foto"} );
        }
    
        $vk_data_users_id = array();
        foreach ($data as $inf){
            $vk_data_users_id[$inf["uid"]] = array("uid"=>$inf["uid"], "first_name"=>$inf["first_name"], "last_name"=>$inf["last_name"], "foto"=>$inf["foto"], "photo_50"=>$inf["photo_50"] );
        }

        foreach ($_data_users as $row)   {
          $cont = false;
          // Сравниваем списки из контака и из базы данных, если есть юзер то не добавляем

          //if($row["idvk"]==$inf["uid"]){
            if(!empty($vk_data_users_id[$row["idvk"]])){ 
              $cont = true;
            }
          if($cont) continue;              
           $this->deleteuser($row["idvk"], $row["name"], $row["surname"], $row["status"], $row["foto"]); 
        }
        
        
    }
    
    // Обновить юзера
    public function updateuser($idvk, $name, $surname, $status, $foto){
        $query =  "UPDATE  ".$this->table." SET  `idvk` =  '".$idvk."', `name` =  '".$name."', `surname` =  '".$surname."', `status` =  '".$status."', `foto` =  '".$foto."'  WHERE  `idvk` =".$idvk;
        $this->user_add[] = array("idvk"=>$idvk, "name"=>$name, "surname"=>$surname, "status"=>$status, "foto"=>$foto, "operation"=>"Обновление");
         if (!mysql_query($query)) 
          $this->showerror ();
    }
    
    // Обновить юзеров
    public function updateusers($data){
         
        // Получаем весь список из базы данных
        $query = "SELECT * FROM ".$this->table."";
        $data_table = mysql_query($query);
        if (!$data_table) 
          $this->showerror ();
        
        // Заполняем массив данными из базы
        $_data_users_id = array();
        while($row=mysql_fetch_object($data_table)){
            $_data_users_id[$row->{"idvk"}] = array("idvk"=>$row->{"idvk"}, "name"=>$row->{"name"}, "surname"=>$row->{"surname"}, "status"=>$row->{"status"}, "foto"=>$row->{"foto"} );
        }
        

        $vk_data_users_id = array();
        foreach ($data as $inf){
            $vk_data_users_id[$inf["uid"]] = array("uid"=>$inf["uid"], "first_name"=>$inf["first_name"], "last_name"=>$inf["last_name"], "foto"=>$inf["foto"], "photo_50"=>$inf["photo_50"] );
        }
        
        

        foreach ($data as $inf){
          $cont = false;
          // Сравниваем списки из контака и из базы данных, если данные в таблице не верны то обновляем
            if(!empty($_data_users_id[$inf["uid"]])&&$vk_data_users_id[$inf["uid"]])
              if($_data_users_id[$inf["uid"]]["name"]==$vk_data_users_id[$inf["uid"]]["first_name"]&&
                      $_data_users_id[$inf["uid"]]["surname"]==$vk_data_users_id[$inf["uid"]]["last_name"]&&
                            $_data_users_id[$inf["uid"]]["foto"]==$vk_data_users_id[$inf["uid"]]["photo_50"]){
                  $cont = true;
              }
          
          
          if($cont) continue;
    
            
   
            $foto = "";
            if($inf["photo_50"]!="") $foto = $inf["photo_50"];
                                
           $this->updateuser($inf["uid"], $inf["first_name"], $inf["last_name"], "0", $foto); 
            
        }
        
        
    }
    
    
    // Отрисовка таблица из базы данных
    public function render(){
        $render = new render();
        $query = "SELECT * FROM ".$this->table."";
              
        $data = mysql_query($query);
        if (!$data) 
          $this->showerror ();
        $render->render_table("Таблица из базы данных", $data, array("idvk"=>"idvk", "name"=>"Имя", "surname"=>"Фамилия", "status"=>"Статус", "foto"=>"Фото" ));
       
    }
    
}
?>