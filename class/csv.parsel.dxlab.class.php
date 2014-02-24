<?php

/*
 * Работа с csv файлом
 */

class csv {
    
    function csv(){

    }
    
    
// Получить массив данных
function getArray($Filecsv, $_array){
    $row = 1;
    $array = array();
    if (($handle = fopen($Filecsv, "r")) !== FALSE) {
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
          //  echo "<p> $num полей в строке $row: <br /></p>\n";
            $row++;
            $arrayValue = array();
            for ($c=0; $c < $num; $c++) {
                $arrayValue[] = $data[$c];
            }

         $array[] = array_combine($_array, $arrayValue);
         $array[count($array)-1]["status"] = 0;
        }
        
        fclose($handle);
    }else
    {
        die("Не удалось открыть файл: ".$Filecsv);
    }
    return $array;
}
    
}


?>
