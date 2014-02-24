<?php
/* Тут отрисовываем таблицу с юзерами если надо
 * 
 */

class render{

    
public function render(){
    
}
    
// Выводим юзеров из массива с сайта вконтакте
public function render_vk_users($title, $info_user){
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >
    <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <h1><?php print $title; ?></h1>
    <table style='border: 2px solid black;'>
    <th>№</th><th>ID</th><th>Имя</th><th>Фото</th><th>Статус</th>

    <?php 
    $_i = 0;
    foreach ($info_user as $inf){
        $_i++;
        $foto = "";
        if($inf["photo_max_orig"]!="") $foto = $inf["photo_max_orig"];
         elseif($inf["photo_max"]!="") $foto = $inf["photo_max"];
             elseif($inf["photo_400_orig"]!="") $foto = $inf["photo_400_orig"];
                 elseif($inf["photo_200"]!="") $foto = $inf["photo_200"];
                     elseif($inf["photo_200_orig"]!="") $foto = $inf["photo_200_orig"];
                         elseif($inf["photo_100"]!="") $foto = $inf["photo_100"];
                             elseif($inf["photo_50"]!="") $foto = $inf["photo_50"];

        print "<tr style='border: 2px solid black;'>";

        print "<td style='border: 2px solid black;'>".$_i."</td>";
        print "<td style='border: 2px solid black;'>".$inf["uid"]."</td>";
        print "<td style='border: 2px solid black;'><a href='http://vk.com/id".$inf["uid"]."' target='_blank'>".$inf["first_name"]." ".$inf["last_name"]."</a></td>";
        //print "<td style='border: 2px solid black;'><img src='".$foto."' style='width:256px;' /></td>";

        print "</tr>";

    }
    ?> 
    </table>         
    </body>
    </html>
     <?php
}





// Выводим юзеров из таблицы
public function render_table($title, $info_user, $array){
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >
    <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <h1><?php print $title; ?></h1>
    <table style='border: 2px solid black;'>

    <th>№</th>
    <?php
    foreach ($array as $key=>$th) print "<th>".$th."</th>";
    ?>

    <?php 
    $_i = 0;
     while($row=mysql_fetch_object($info_user)){
    $_i++;


        print "<tr style='border: 2px solid black;'>";
            print "<td>".$_i."</td>";
            foreach ($array as $key=>$th) print "<td>".$row->{$key}."</td>";
        print "</tr>";

    }
    ?> 
    </table>         
    </body>
    </html>
     <?php
}


// Выводим пользователей из массива
public function render_table_array($title, $info_user, $array){
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >
    <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <h1><?php print $title; ?></h1>
    <table style='border: 2px solid black;'>

    <th>№</th>
    <?php
    foreach ($array as $key=>$th) print "<th>".$th."</th>";
    ?>

    <?php 
    $_i = 0;
     foreach($info_user as $row){
    $_i++;


        print "<tr style='border: 2px solid black;'>";
            print "<td>".$_i."</td>";
            foreach ($array as $key=>$th) print "<td>".$row[$key]."</td>";
        print "</tr>";

    }
    ?> 
    </table>         
    </body>
    </html>
     <?php
}

// Выводим массив и csv файла
public function render_csv_srray($title, $csv, $array_template){
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >
    <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <h1><?php print $title; ?></h1>
    <table style='border: 2px solid black;'>

    <th>№</th>
    <?php
    foreach ($array_template as $key=>$th) print "<th>".$th."</th>";
    ?>

    <?php 
    $_i = 0;
     foreach($csv as $row){
    $_i++;


        print "<tr style='border: 2px solid black;'>";
            print "<td>".$_i."</td>";
            foreach ($array_template as $key){ print "<td>".$row[$key]."</td>";}
        print "</tr>";

    }
    ?> 
    </table>         
    </body>
    </html>
     <?php
}


// Выводим таблицу с информацией сайта который парсим
public function render_url_info($title, $arrayData){
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >
    <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <h1><?php print $title; ?></h1>
    <table style='border: 2px solid black;'>

    <th>№</th>
    <th>Полное имя</th>
    <th>Цена</th>
    <th>Главное изоброжение</th>
    <th>Параметры</th>
    <th>Описание Товара</th>

    <?php 
    $_i = 0;
     foreach($arrayData as $key=>$_row){
       $_i++;print "<tr style='border: 2px solid black;'>";
       print "<td>".$_i."</td>";
            foreach($_row as $key=>$row){


                       
                 //      foreach ($array_template as $key){ print "<td>".$row[$key]."</td>";}
                       // Главное изоброжение
                       if($key=="imgT")print "<td><img src='".$row[0]."' style='width:128px;'/></td>";
                       // Вывод параметром
                       if($key=="param"){
                          print "<td>";
                            foreach($row as $p){ print $p."<br/>";}
                          print "</td>";
                       }
                       // Цена
                       if($key=="price")print "<td>".$row[0]."</td>";
                       
                       // Полное имя
                       if($key=="h1")print "<td>".$row[0]."</td>";
                       
                       // Описание товара
                       if($key=="model_desc")print "<td>".$row[0]."</td>";
                       
         }
     print "</tr>";
    }
    ?> 
    </table>         
    </body>
    </html>
     <?php
}

}
?>

