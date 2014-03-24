<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru-ru" lang="ru-ru" >
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>List Orders</title>
<style type="text/css">
table {
  border-collapse:collapse;
}
td {
  border:1px solid #eeeeee;
  text-align:center;
  padding:5px;
}
a {
  text-decoration:none;
}
a:hover {
  text-decoration:underline;
}
.new {
  font-weight:bold;
  color:#ff0000;
}
.new2 {
  color:#C928AE;
}
.avail {
  font-weight:bold;
  color:#0000ff;
}
.subm {
  color:#009900;
}
.canc {
  color:#bbbbbb;
}
.pay {
  font-weight:bold;
  color:#ffbb00;
}
.std {
  color:#000000;
}
.fin {
  color:#76ECFC;
}
.orderdate {
  font-size:11px;
  color:#aaaaaa;
}
</style>
</head>
<body>
<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//error_reporting(E_STRICT);
//date_default_timezone_set('Europe/Moscow');
//require_once('mailer/class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
$managers[1201]='Подковина Елена';
$managers[1202]='Белозерова Ольга';
$managers[1203]='Завулунова Вера';
$managers[1204]='Никитина Надежда';
$managers[1205]='Устинова Татьяна';

$jprefix = 'vlif9';
/*
$mysite[0]['db'] = 'tversport';
$mysite[0]['url'] = 'tversport.ru';
$mysite[0]['site'] = 'ТверьСпорт';
$mysite[0]['org'] = 'ООО &quot;ТверьСпорт Ру&quot;';
$mysite[0]['reg'] = 'Тверь';
$mysite[0]['suf'] = 'tvr';
$mysite[0]['mngr'] = 1202;
*/

$mysite[0]['db'] = 'tvrspdbmain';
$mysite[0]['url'] = 'tversport.ru';
$mysite[0]['site'] = 'ТверьСпорт';
$mysite[0]['org'] = 'ООО &quot;ТверьСпорт Ру&quot;';
$mysite[0]['reg'] = 'Тверь';
$mysite[0]['suf'] = 'tvr';
$mysite[0]['mngr'] = 1202;

$mysite[1]['db'] = 'yarhsprtdb';
$mysite[1]['url'] = 'yaroslavl.heartsport.ru';
$mysite[1]['site'] = 'HeartSport-Ярославль';
$mysite[1]['org'] = 'ООО &quot;Спорт Ру&quot;';
$mysite[1]['reg'] = 'Ярославль';
$mysite[1]['suf'] = 'yar';
$mysite[1]['mngr'] = 1202;

$mysite[2]['db'] = 'vlhsprtdb';
$mysite[2]['url'] = 'vladimir.heartsport.ru';
$mysite[2]['site'] = 'HeartSport-Владимир';
$mysite[2]['org'] = 'ООО &quot;Спорт Ру&quot;';
$mysite[2]['reg'] = 'Владимир';
$mysite[2]['suf'] = 'vl';
$mysite[2]['mngr'] = 1203;

$mysite[3]['db'] = 'nnhsprtdb';
$mysite[3]['url'] = 'nnovgorod.heartsport.ru';
$mysite[3]['site'] = 'HeartSport-Нижний Новгород';
$mysite[3]['org'] = 'ООО &quot;Спорт Ру&quot;';
$mysite[3]['reg'] = 'Н.Новгород';
$mysite[3]['suf'] = 'nn';
$mysite[3]['mngr'] = 1204;

$mysite[4]['db'] = 'kazhsprtdb';
$mysite[4]['url'] = 'kazan.heartsport.ru';
$mysite[4]['site'] = 'HeartSport-Казань';
$mysite[4]['org'] = 'ООО &quot;Спорт Ру&quot;';
$mysite[4]['reg'] = 'Казань';
$mysite[4]['suf'] = 'kaz';
$mysite[4]['mngr'] = 1203;

$mysite[5]['db'] = 'ufahsprtdb';
$mysite[5]['url'] = 'ufa.heartsport.ru';
$mysite[5]['site'] = 'HeartSport-Уфа';
$mysite[5]['org'] = 'ООО &quot;Спорт Ру&quot;';
$mysite[5]['reg'] = 'Уфа';
$mysite[5]['suf'] = 'ufa';
$mysite[5]['mngr'] = 1204;

$mysite[6]['db'] = 'samhsprtdb';
$mysite[6]['url'] = 'samara.heartsport.ru';
$mysite[6]['site'] = 'HeartSport-Самара';
$mysite[6]['org'] = 'ООО &quot;Спорт Ру&quot;';
$mysite[6]['reg'] = 'Самара';
$mysite[6]['suf'] = 'sam';
$mysite[6]['mngr'] = 1202;

$mysite[7]['db'] = 'chlhsprtdb';
$mysite[7]['url'] = 'chelyabinsk.heartsport.ru';
$mysite[7]['site'] = 'HeartSport-Челябинск';
$mysite[7]['org'] = 'ООО &quot;Спорт Ру&quot;';
$mysite[7]['reg'] = 'Челябинск';
$mysite[7]['suf'] = 'chl';
$mysite[7]['mngr'] = 1205;

$mysite[8]['db'] = 'ekbhsprtdb';
$mysite[8]['url'] = 'ekaterinburg.heartsport.ru';
$mysite[8]['site'] = 'HeartSport-Екатеринбург';
$mysite[8]['org'] = 'ООО &quot;Спорт Ру&quot;';
$mysite[8]['reg'] = 'Екатеринбург';
$mysite[8]['suf'] = 'ekb';
$mysite[8]['mngr'] = 1205;

$mysite[9]['db'] = 'tlthsprtdb';
$mysite[9]['url'] = 'tolyatti.heartsport.ru';
$mysite[9]['site'] = 'HeartSport-Тольятти';
$mysite[9]['org'] = 'ООО &quot;Спорт Ру&quot;';
$mysite[9]['reg'] = 'Тольятти';
$mysite[9]['suf'] = 'tlt';
$mysite[9]['mngr'] = 1205;

$db = mysql_connect("localhost", "root", "HwN77fBaqd");
//$db = mysql_connect("localhost", "root", "12345678");
if (!$db) {
	echo "Не удается подключиться к БД";
}
else {

mysql_query('SET NAMES utf8');
$symbs = array(" ", ":", "-");
$counter = 0;
foreach($mysite as $cursite)	{
 mysql_select_db($cursite['db']);
 $result = mysql_query("SELECT `order_id`,`order_number`,`order_date`,`order_status`,`order_created` FROM `".$jprefix."_jshopping_orders` ORDER BY `order_id` DESC LIMIT 100");
 if (mysql_num_rows($result) >0){
	$i = 0;
	while($row=mysql_fetch_row($result)) {
	    $dataarr[$counter][$i]['order_id'] = $row[0];
	    $dataarr[$counter][$i]['order_number'] = $row[1];
	    $phpdate = strtotime($row[2]);
	    $mysqldate = date('H:i d.m', $phpdate );
	    $dataarr[$counter][$i]['order_date'] = $mysqldate;
	    $dataarr[$counter][$i]['order_status'] = $row[3];
	    $dataarr[$counter][$i]['order_created'] = $row[4];
	    $i+=1;
	}	
    $counter+=1;
  }	
}

echo '<table>';
echo '<tr>';
   for ($k = 0; $k < $counter; $k++) {
    echo '<td>';
        if(isset($mysite[$k]['reg'])) {
         echo $mysite[$k]['reg'];
	} else echo '&nbsp;';
    echo '</td>';
   }
  echo '</tr>';

for ($j = 0; $j < 100; $j++) {
  echo '<tr>';
   for ($k = 0; $k < $counter; $k++) {
    echo '<td>';
        if(isset($dataarr[$k][$j]['order_id'])) {
         if($dataarr[$k][$j]['order_status'] == 1) {
         $sclass="new";
	    if($dataarr[$k][$j]['order_created'] == 0)
		{
		   $sclass="new2";
		}
	} elseif ($dataarr[$k][$j]['order_status'] == 8) {
	 $sclass="avail";
	} elseif ($dataarr[$k][$j]['order_status'] == 2) {
	 $sclass="subm";
	} elseif ($dataarr[$k][$j]['order_status'] == 3) {
         $sclass="canc";
	} elseif ($dataarr[$k][$j]['order_status'] == 6) {
	 $sclass="pay";
	} elseif ($dataarr[$k][$j]['order_status'] == 7) {
	 $sclass="fin";
	} else {
	 $sclass="std";
	}
         echo '<a href="http://'.$mysite[$k]['url'].'/administrator/index.php?option=com_jshopping&amp;controller=orders&amp;task=show&amp;order_id='.$dataarr[$k][$j]['order_id'].'" target="_blank"><span class="'.$sclass.'">'.$dataarr[$k][$j]['order_number'].'</span></a>';
	 echo '<br /><span class="orderdate">'.$dataarr[$k][$j]['order_date'].'</span>';
	} else echo '&nbsp;';
    echo '</td>';
   }
  echo '</tr>';
}
echo '</table>';

	echo '<p><span class="new">Новый</span> <span class="new2">Не закончен</span> <span class="avail">Наличие</span> <span class="pay">Оплачен</span> <span class="subm">Подтвержден</span> <span class="fin">Выполнен</span> <span class="canc">Отменен</span></p>';
}

?>

</body>
</html>
