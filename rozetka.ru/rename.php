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
        print "<tr>";
           print "<td>".$i."</td>";
           print "<td>".(string) $item->Ид[0]."</td>";
           print "<td>".(string) $item->Наименование[0]."</td>";
           print "<td>".trim((string) $item->Артикул[0])."</td>";
           
            
           
           if(file_exists("data/foto/".trim((string) $item->Артикул[0]).".png"))
           rename("data/foto/".trim((string) $item->Артикул[0]).".png", "data/foto/".(string) $item->Ид[0].".png");
           
           if(file_exists("data/foto/".trim((string) $item->Артикул[0]).".PNG"))
           rename("data/foto/".trim((string) $item->Артикул[0]).".PNG", "data/foto/".(string) $item->Ид[0].".PNG");

           if(file_exists("data/foto/".trim((string) $item->Артикул[0]).".jpg"))
           rename("data/foto/".trim((string) $item->Артикул[0]).".jpg", "data/foto/".(string) $item->Ид[0].".jpg");
           
           if(file_exists("data/foto/".trim((string) $item->Артикул[0]).".JPG"))
           rename("data/foto/".trim((string) $item->Артикул[0]).".JPG", "data/foto/".(string) $item->Ид[0].".JPG");
           
        
        print "</tr>";
        //echo '[', (string) $item->Ид[0], '] ', (string) $item->Наименование[0], "\n";
       }
       print "</table>";
?>

    </body>
</html>