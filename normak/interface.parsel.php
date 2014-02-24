<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Парсинг страниц в базу</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <style>
  .ui-progressbar {
    position: relative;
    display: none;
  }
  .progress-label {
    position: absolute;
    left: 50%;
    top: 4px;
    font-weight: bold;
    text-shadow: 1px 1px 0 #fff;
    display: none;
  }
  </style>
  <script> 
      
  
  
 
  $( document ).ready(function() {
      
      
$(".start").on( "click", function() {
          



    $(function() {
    var progressbar = $( "#progressbar" ),
      progressLabel = $( ".progress-label" );
      
         // Старт парсинга
         $.ajax({
           type: "POST",
           url: "http://127.0.0.1/scripts.ru/normak/index.parsel.php?q=p_url_site",
           success: function (data, textStatus ){
               alert(data);
           }
         });
      
    $("#progressbar").css("display","block");
    $(".progress-label").css("display","block");
    $(".start").css("display","none");
    
    progressbar.progressbar({
      value: false,
      change: function() {
        progressLabel.text( progressbar.progressbar( "value" ) + "%" );
      },
      complete: function() {
        progressLabel.text( "Complete!" );
      }
    });
 
    function progress() {
      var val = progressbar.progressbar( "value" ) || 0;
 
      // Выполняем обновление
      if ( val < 99 ) {
        $.ajax({
           type: "POST",
           url: "http://127.0.0.1/scripts.ru/normak/json.php",
           success: function (data, textStatus ){
             obj = JSON.parse(data);
             progressbar.progressbar( "value", Math.round(((obj.to/obj.max)*100)) );
           }
         });
        setTimeout( progress, 1000 );
      }
      
    }



 
     setTimeout( progress, 3000 );
    });
  
});
      
      
  }); 
 
  </script>
</head>
<body>
 
<div id="progressbar"><div class="progress-label">Loading...</div></div>
<div><input class="start" type="button" value="СТАРТ"/></div>
 
</body>
</html>