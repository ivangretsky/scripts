<?php

/**
 * VKAPI class for vk.com social network
 *
 * @package server API methods
 * @link http://vk.com/developers.php
 * @autor Oleg Illarionov
 * @version 1.0
 */
 

class vkapi {
	var $api_secret;
	var $app_id;
	var $api_url;
        var $redirect;
	
        // Авторизация
        function log_on(){
           // ini_set("safe_mode",false);
         //   ini_set("open_basedir",false);
            
            // Подключаем класс curl
            require_once ('curl.class.php');

            // Данные для входа
            define ("EMAIL", "89201758567"); 
            define ("PASSWORD", "");

            // Создаем объект curl
            $curl = new curl;
            // Инициализируем curl
            $curl->init();
            // Устанавливаем USER_AGENT
            $curl->set_useragent('Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.1');
            // Обрабатываем заголовок Location
            $curl->set_followlocation(1);
            // Запрещаем вывод данных в браузер
            $curl->set_returntransfer(1);
            // Устанавливаем ссылку для первого захода
            $curl->set_url('http://vk.com/');
            // Нам необходимо вывести заголовки,
            // поэтому устанавливаем единицу для вывода головы документа
            $curl->set_header(1);
            // Получаем тело документа и одновременно устанавливаем cookies
            $curl->set_cookie($curl->exec(false));
            
            // Установить имя файла для куков
            $curl->set_cookiefile("cookvk2");
            // POST запрос будем передавать по этой ссылке
            $curl->set_url('http://login.vk.com/?act=login');
            //$curl->set_url("https://oauth.vk.com/authorize");
            // Собственно сам POST запрос
          //  $curl->set_post("al_frame=1&captcha_key=&captcha_sid=&email=". EMAIL ."&expire=&from_host=vkontakte.ru&pass=". PASSWORD ."&q=1");
            $curl->set_post("email=". EMAIL ."&scope=offline&pass=". PASSWORD ."&client_id=".$this->app_id."&client_secret=".$this->api_secret."&redirect_uri=".$this->redirect."?response_type=code");
            
            // Получаем тело документа и одновременно устанавливаем cookies
            $curl->set_cookie($curl->exec(false));


            // Указываем реферера
            //$curl->set_referer('http://login.vk.com/?act=login');
            // Устанавливаем конечную ссылку
           // $curl->set_url('http://vk.com/login.php?');
            // Отключаем вывод головы документа
            $curl->set_header(0);
            
            print_r(curl_getinfo($curl->ch));
            // Выполняем
            //echo $curl->exec(false);
            //echo $curl->info();
         
             
            $curl->close(); 
        }

        /*
         *  $app_id     - api приложения
         *  $api_secret - секретный код
         *  $get_token  - нужно ли получать токен или нет
         *  $_redirect  - Куда будет все перенаправление
         *  $api_url    - Методы api вконтакте
         */
        
	function vkapi($app_id, $api_secret, $get_token = false, $_redirect = "http://127.0.0.1/scripts.ru/vk/indexnew.php", $api_url = 'api.vk.com/method/') {
		$this->app_id = $app_id;
		$this->api_secret = $api_secret;
                $this->redirect = $_redirect;
		if (!strstr($api_url, 'http://')) $api_url = 'https://'.$api_url;
		$this->api_url = $api_url;
                // Авторизация через курл
               //$this->log_on();
                // Получаем токен
                if($get_token)$this->gettoken();
	}
	
        function gettoken(){
            $code = $_GET['code'];
            if($_GET["clear_token"]=="0") unset($_SESSION["tok"]);
            // Если нет токена
            if(empty($_SESSION["tok"]))
            // Если есть код пробуем получить токен
            if (!empty($_GET['code'])){
            $json = file_get_contents('https://oauth.vk.com/access_token?client_id='.$this->app_id.'&code='.$code.'&client_secret='.$this->api_secret."&redirect_uri=$this->redirect?response_type=code");

            $obj = json_decode($json);
            $tok= $obj->{'access_token'};
            $_SESSION["tok"] = $tok;
            // если нет кода просим получить его
            }else{
               
                ?>
                    <a href="http://api.vkontakte.ru/oauth/authorize?client_id=<?=$this->app_id?>&scope=offline&redirect_uri=<?php print $this->redirect; ?>?response_type=code">Получить токен</a>
 <!-- <a href="https://login.vk.com/?act=login&soft=1&expire=0&_origin=https://oauth.vk.com/?response_type=code<?php print "&email=". EMAIL ."&scope=offline&pass=". PASSWORD; ?>&client_id=<?=$this->app_id?>&scope=offline&redirect_uri=<?php print $this->redirect; ?>?response_type=code">Получить токен</a> -->
                <?php
                exit();
            }
        }
        
	function api($method,$params=false) {
		if (!$params) $params = array(); 
		$params['api_id'] = $this->app_id;
		$params['v'] = '3.0';
		//$params['method'] = $method;
		$params['timestamp'] = time();
		$params['format'] = 'json';
		$params['random'] = rand(0,10000);
		ksort($params);
		$sig = '';
		foreach($params as $k=>$v) {
			$sig .= $k.'='.$v;
		}
		$sig .= $this->api_secret;
		$params['sig'] = md5($sig);
		$query = $this->api_url.$method.'?'.$this->params($params);
		$res = file_get_contents($query);
		return json_decode($res, true);
	}
	
	function params($params) {
		$pice = array();
		foreach($params as $k=>$v) {
			$pice[] = $k.'='.urlencode($v);
		}
		return implode('&',$pice);
	}
        
        function get_info_user($params = false){
           // $str = "https://api.vk.com/method/users.get?fields=photo_200&user_id=$id_user&v=5.5&access_token=".$_SESSION["tok"];
           // return json_decode(file_get_contents($str), true);
            return $this->api("users.get", $params);
        }
        
        function input_api_params($_api, $params = false, $_code){
            $str = "https://api.vk.com/method/execute?code=".$_code."&access_token=".$_SESSION["tok"];
            print $str;
            print file_get_contents($str);
            return json_decode(file_get_contents($str), true);
           // return $this->api($_api, $params);
        }
        
}
?>
