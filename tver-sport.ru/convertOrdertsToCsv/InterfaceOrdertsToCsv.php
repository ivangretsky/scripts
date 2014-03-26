<?php
set_time_limit(0);
ini_set("display_errors", 1); 
error_reporting(E_ALL);
// Подключаем основной класс
include_once 'convertOrdertsToCsvClass.php';
// Создаем класс
$convertOrdertsToCsv = new convertOrdertsToCsv(array("db"=>"tversportru", "host"=>"127.0.0.1", "user"=>"root", "pass"=>"", "jprefix"=>"vlif9"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >

<head>

  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery.multiselect.js"></script>
 
<script type="text/javascript">
    $(function(){
            $(".site").multiselect();
            $(".supplier").multiselect();
    });
    
    $(document).ready(function() {
     $(".start_filter").on("click", function(){
      // alert( $("select").serialize());
         //window.location = "ya.ru";
     });
    });
    
</script>
    
  
</head>
<body>
    
<form name="SiteSupplier" method="get" action="convertOrdertsToCsv.php">
    <h1>Города</h1>
    <select multiple="multiple" name="site[]" class="site">
    <?php
    $convertOrdertsToCsv->printSite();
    ?>
    </select>

    <h1>Поставщики</h1>
    <select multiple="multiple" name="supplier[]" class="supplier">
    <?php
    $convertOrdertsToCsv->printSupplier();
    ?>
    </select>
 
    <p>
        <input type="submit" class="start_filter" value="Начать" />
    </p>
    
</form>
    
</body>
</html>