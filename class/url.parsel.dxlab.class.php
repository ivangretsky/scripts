<?php
include_once '../class/simple_html_dom.php';
/* 
 * Парсел страниц
 */

class urlParsel{
    // Адрес того чего парсим
    public $url = "";
    // переменная для хранения данных
    public $html;
    // Основной домен
    public $urlSite;
    
    function urlParsel($_urlSite, $_url){
        $this->url     = $_url;
        $this->urlSite = $_urlSite;
        $this->html = file_get_html($this->url);
    }
    
    
    // Получить img в массиве
    public function getImg($selector){
        $array = array();
        foreach($this->html->find($selector) as $element) 
        $array[] = $this->urlSite.$element->src;
        return $array;
    }
    
    // Получить text в массиве
    public function getText($selector){
        $array = array();
        foreach($this->html->find($selector) as $element)
        $array[] = $element->plaintext;
        return $array;
    }
    
    
    
}

?>