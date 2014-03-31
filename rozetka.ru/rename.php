<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  
</head>
<style>
    li{
        padding-left: 5px;
    }
</style>
<body>
<?php

$FileName = "data/kat.xml";
 // Загрузка xml файла
        if (file_exists($FileName)) {
                $xml = simplexml_load_file($FileName);
        } else {
                exit('Не удалось открыть файл '.$FileName);
        }
        
       $array_data = array();
       print "<h1>Таблица каталога</h1><table><th>№</th><th>ИД</th><th>Имя</th><th>Артикул</th>";
       $i = 0;
       //foreach ($xml->ПакетПредложений->Предложения[0] as $item) { для прайса
       foreach ($xml->Каталог->Товары[0] as $item) {
       $i++;
       $file_name = iconv("utf-8", "windows-1251", trim((string) $item->Артикул[0]));
       $file_name = str_replace("  ", " ", $file_name);
        print "<tr>";
           print "<td>".$i."</td>";
           print "<td>".(string) $item->Ид[0]."</td>";
           print "<td>".(string) $item->Наименование[0]."</td>";
           print "<td>".$file_name."</td>";
           
            
           
           
//           if(file_exists("data/foto/".$file_name.".png"))
//           rename("data/foto/".$file_name.".png", "data/foto/".(string) $item->Ид[0].".png");
//           
//           if(file_exists("data/foto/".$file_name.".PNG"))
//           rename("data/foto/".$file_name.".PNG", "data/foto/".(string) $item->Ид[0].".PNG");

           if(file_exists("data/foto/".$file_name.".jpg"))
           rename("data/foto/".$file_name.".jpg", "data/foto/"
				   .iconv("utf-8", "windows-1251", (string) $item->Ид[0])
				   .".jpg");
           
//           if(file_exists("data/foto/".$file_name.".JPG"))
//           rename("data/foto/".$file_name.".JPG", "data/foto/".(string) $item->Ид[0].".JPG");
           
        
        print "</tr>";
        //echo '[', (string) $item->Ид[0], '] ', (string) $item->Наименование[0], "\n";
       }
       print "</table>";
?>

    </body>
</html>