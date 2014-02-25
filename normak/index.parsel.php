<?php
/*
 * p_xml_url  - Парсим xml файл с ссылками в базу данных
 * p_url_site - Парсим товары с сайта в базу данных
 * update_csv_db - Обновить таблицу
 * update_tversport - Загружаем в базу тверь спорта товары с сайта
 */
// Командный файл для парсинга
set_time_limit(0);
ini_set("display_errors", 1); 

include_once '../class/csv.parsel.dxlab.class.php';
include_once '../class/update.db.class.php';
include_once '../class/shimano.class.php';
include_once '../class/render.class.php';


$render = new render();

// Парисить и вывести csv файл
/*
$parselCsv = new csv();
$arrayDataCsv = $parselCsv->getArray("data/nado.csv", array("name", "price", "id"));
$render->render_csv_srray("Данные из csv файла", $arrayDataCsv, array("name", "price", "id"));
*/

// Парсить и вывести товар в таблицу
/*
$urlParsel = new urlParsel( "http://shimano.ru/", "http://shimano.ru/models/details/shimano-exage-bx-stc-specimen-12-2-75lb.html");
$re_array[] = array( "h1"=>$urlParsel->getText         ("h1"),
                     "price"=>$urlParsel->getText      (".price"),
                     "imgT"=>$urlParsel->getImg        ("a.highslide img"),
                     "param"=>$urlParsel->getText      (".params"),
                     "model_desc"=>$urlParsel->getText (".model_desc"));
$render->render_url_info("Данные парсинга сайта", $re_array);
*/


// Сначало заливаем сслыки в базу
if($_GET["q"]=="p_xml_url"){
    $shimano = new shimano(array("db"=>"parsel", "host"=>"127.0.0.1", "user"=>"root", "pass"=>"", "table"=>"shimano.ru"));
    $shimano->parselXmlUrl("data/shimano-sitemap.xml");
}
// Потом сайт парсим
if($_GET["q"]=="p_url_site"){
    $shimano = new shimano(array("db"=>"parsel", "host"=>"127.0.0.1", "user"=>"root", "pass"=>"", "table"=>"shimano.ru"));
    $shimano->parselUrlToDB("siteinfo");
}

// Добавляем найденые товары в таблицы
if($_GET["q"]=="update_csv_db") {
    $updateDB = new updateDB(array("db"=>"parsel", "host"=>"127.0.0.1", "user"=>"root", "pass"=>"", "table"=>"siteinfo"));
    $updateDB->updateCsvToTable("data/nado.csv", "siteinfo");
}

// Добавляем товары на сайт
if($_GET["q"]=="update_tversport") {
    $updateDB = new updateDB(array("db"=>"parsel", "host"=>"127.0.0.1", "user"=>"root", "pass"=>"", "table"=>"siteinfo"));
    $updateDB->updateDBTverSport();
}

?>