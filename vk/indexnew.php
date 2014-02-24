
<?php
/*require 'vkapi.class.php';

$api_id = 1234; // Insert here id of your application
$secret_key = ' your secret key '; // Insert here secret key of your application

$VK = new vkapi($api_id, $secret_key);

$resp = $VK->api('getProfiles', array('uids'=>'1,6492'));

print_r($resp);
*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >

<head>

  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<?php

set_time_limit(0);

session_start();


$secret = 'e5Fv547h85yQ2Hs5Oful'; //секретный ключ вашего приложения
$idapp = '4084400'; //id вашего приложения

// Получаем юзеров
require '/class/vkapi.class.php';
$VK = new vkapi( $idapp, $secret); //подключаем класс

//34792066
//получаем список участников группы, один запрос 1000 участников
$groups = $VK->api( 'groups.getMembers', array('gid'=>'34792066','access_token'=>$_SESSION["tok"]));
echo "<br /><a href='?clear_token=0'>Очистить токен</a><br/>";
//print_r($groups["response"]["users"]);
?>

    <table style='border: 2px solid black;'>
    <th>№</th><th>ID</th><th>Имя</th><th>Фото</th>
<?php 

foreach ($groups["response"]["users"] as $id)
$ids .= ',' .$id;
$ids = substr($ids, 1, strlen($ids)-1);
$info_user = $VK->get_info_user(array("fields"  => "photo_50, photo_100, photo_200_orig, photo_200, photo_400_orig, photo_max, photo_max_orig", "user_ids" => $ids, "v" => "5.5"));
$_i = 0;
foreach ($info_user["response"] as $inf){
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
    print "<td style='border: 2px solid black;'><img src='".$foto."' style='width:256px;' /></td>";
   
    print "</tr>";

}

?>
        
</table>    
    

</body>
</html>