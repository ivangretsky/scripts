<?php
//***************************************************//
//													 //
//				Class for cURL		                 //
//   *********************************************   //
//	 Author: SworD									 //
//   *********************************************   //
//   Web-site: swordmedia.ru						 //
//                                                   //
//***************************************************//

class curl {

	var $ch;
	var $httpget = '';	
	var $post = '';
	var $header = 1;
	var $httpheader = array ();
	var $cookie = '';
	var $proxy = '';
	var $verbose = 0;
	var $referer = '';
	var $autoreferer = 0;
	var $writeheader = '';
	var $agent = '';
	var $url = '';
	var $followlocation = 1;
	var $returntransfer = 1;
	var $ssl_verifypeer = 0;
	var $ssl_verifyhost = 2;
	var $sslcert = '';
	var $sslkey = '';
	var $cainfo = '';
	var $cookiefile = '';
	var $timeout = 0;
	var $connect_time = 0;
	var $encoding = 0;
	var $interface = '';
	
	function init (){
		$this->ch = curl_init();
	}
	
	function set_httpget ($httpget){
		$this->httpget = $httpget;
	}
	
	function set_post ($post){
		$this->post = $post;		
	}
        

	
	function set_referer ($referer){
		$this->referer = $referer;
	}
	
	function set_autoreferer ($autoreferer){
		$this->autoreferer = $autoreferer;
	}
	
	function set_useragent ($agent){
		$this->agent = $agent;
	}
	
	function set_header ($header){
		$this->header = $header;
	}
	
	function set_cookie ($head){
		$allsetcookies = array();
		$allpaircookies = array();
		$bufcookie = array();

		preg_match_all('/Set-Cookie:(.*?)path=\//is', $head, $allsetcookies, PREG_SET_ORDER);

		for ($i = 0; $i < count($allsetcookies); $i++) {
		
			preg_match_all('/(.*?)=(.*?);/is', $allsetcookies[$i][1], $allpaircookies, PREG_SET_ORDER);
			
			for ($j = 0; $j < count($allpaircookies); $j++) {
				$bufcookie[] = $allpaircookies[$j][1].'='.$allpaircookies[$j][2].'; path=/';
			}
		}

		$this->cookie = str_replace('NotChecked', 'Checked', join('; ', $bufcookie));
	}
	
	function clear_cookie (){
		$this->cookie = '';
	}
	
	function set_httpheader ($httpheader){
		$this->httpheader = $httpheader;
	}
	
	function clear_httpheader (){
		$this->httpheader = array ();
	}
	
	function set_encoding ($encoding){
		$this->encoding = $encoding;
	}
	
	function set_url ($url){
		$this->url = $url;
	}
	
	function set_interface ($interface){
		$this->interface = $interface;
	}

	function set_writeheader ($writeheader){	
		$this->writeheader = $writeheader;
	}

	function set_followlocation ($followlocation){
		$this->followlocation = $followlocation;
	}

	function set_returntransfer ($returntransfer){
		$this->returntransfer = $returntransfer;
	}
	
	function set_ssl_verifypeer ($ssl_verifypeer){
		$this->ssl_verifypeer = $ssl_verifypeer;
	}
	
	function set_ssl_verifyhost ($ssl_verifyhost){
		$this->ssl_verifyhost = $ssl_verifyhost;
	}
	
	function set_sslcert ($sslcert) {
		$this->sslcert = $sslcert;
	}
	
	function set_sslkey ($sslkey) {
		$this->sslkey = $sslkey;
	}
	
	function set_cainfo ($cainfo) {
		$this->cainfo = $cainfo;
	}
	
	function set_timeout ($timeout){
		$this->timeout = $timeout;
	}
	
	function set_connect_time ($connect_time){
		$this->connect_time = $connect_time;
	}
	
	function set_cookiefile ($cookiefile){
		$this->cookiefile = $cookiefile;
	}

	function set_proxy ($proxy){
		$this->proxy = $proxy;
	}
	
	function set_verbose ($verbose){
		$this->verbose = $verbose;
	}
	
	function get_error (){
		return curl_errno($this->ch);
	}
	
	function get_http_state (){
		if (curl_getinfo($this->ch, CURLINFO_HTTP_CODE) == 200)
			return 'OK';
	}
	
	function get_url (){
		return curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
	}
	
	function exec ($coding = true){
		
		curl_setopt($this->ch, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->ch, CURLOPT_REFERER, $this->referer);
		curl_setopt($this->ch, CURLOPT_AUTOREFERER, $this->autoreferer);
		curl_setopt($this->ch, CURLOPT_URL, $this->url);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION , $this->followlocation);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,$this->returntransfer);	
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $this->ssl_verifyhost);		
		curl_setopt($this->ch, CURLOPT_HEADER, $this->header);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->connect_time);
		curl_setopt($this->ch, CURLOPT_VERBOSE, $this->verbose);
		
		if ($this->encoding == 1)
			curl_setopt($this->ch, CURLOPT_ENCODING, $this->encoding);
		
		if ($this->interface != '')
			curl_setopt($this->ch, CURLOPT_INTERFACE, $this->interface);
		
		if ($this->httpget != '')
			curl_setopt($this->ch, CURLOPT_HTTPGET, $this->httpget);
		
		if ($this->writeheader != '')
			curl_setopt($this->ch, CURLOPT_WRITEHEADER, $this->writeheader);
		
		if ($this->post != '') {
			curl_setopt($this->ch, CURLOPT_POST, 1);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->post);
		}
		
		if ($this->proxy != '')
			curl_setopt($this->ch, CURLOPT_PROXY, $this->proxy);

		if ($this->cookie != '')
			curl_setopt($this->ch, CURLOPT_COOKIE, $this->cookie);
		
		if (count($this->httpheader) > 0)
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->httpheader);

		if ($this->sslcert != '')
			curl_setopt($this->ch, CURLOPT_SSLCERT, $this->sslcert);
			
		if ($this->sslkey != '')
			curl_setopt($this->ch, CURLOPT_SSLKEY, $this->sslkey);
			
		if ($this->cainfo != '')
			curl_setopt($this->ch, CURLOPT_CAINFO, $this->cainfo);
		
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookiefile);
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookiefile);
		
		$result = curl_exec($this->ch);
		$this->post = '';
		
		if ($coding == true)
			return iconv('UTF-8', 'CP1251', $result);
		else
			return $result;
	}
	
	function info (){
	
		echo "
			<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><style>TABLE.report {border: 1px solid #CCCCCC;font: 1.0em \"Times New Roman\", Times, serif;}TABLE.report TR TD.caption {background:#373737;color:#FFFFFF;font-size:1.2em;text-align:center;}TABLE.report TR.caption_column TD {background:#EEEEEE;text-align:center;font-weight:bold;}TABLE.report TR TD {	border-bottom: 1px solid #C5C5C5;border-right: 1px solid #C5C5C5;padding-left:5px;background:#FFFFFF;}TABLE.report TR TD.left_column {border-left: 1px solid #C5C5C5; font-weight:bold;width:100px;}</style></head><body>
			<table class=\"report\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"80%\">
			<tr><td colspan=\"2\" class=\"left_column caption\">OPTIONS</td></tr>
			<tr class=\"caption_column\"><td class=\"left_column\">Параметры</td><td>Значения</td></tr><tr>
			<tr><td class=\"left_column\">HTTPGET:</td><td>$this->httpget</td></tr>
			<tr><td class=\"left_column\">POST:</td><td>$this->post</td></tr>			
			<tr><td class=\"left_column\">HEADER:</td><td>$this->header</td></tr>
			<tr><td class=\"left_column\">USERAGENT:</td><td>$this->agent</td></tr>
			<tr><td class=\"left_column\">REFERER:</td><td>$this->referer</td></tr>
			<tr><td class=\"left_column\">AUTOREFERER:</td><td>$this->autoreferer</td></tr>
			<tr><td class=\"left_column\">URL:</td><td>$this->url</td></tr>
			<tr><td class=\"left_column\">FOLLOWLOCATION:</td><td>$this->followlocation</td></tr>
			<tr><td class=\"left_column\">RETURNTRANSFER:</td><td>$this->returntransfer</td></tr>
			<tr><td class=\"left_column\">SSL_VERIFYPEER:</td><td>$this->ssl_verifypeer</td></tr>
			<tr><td class=\"left_column\">SSL_VERIFYHOST:</td><td>$this->ssl_verifyhost</td></tr>
			<tr><td class=\"left_column\">SSLCERT:</td><td>$this->sslcert</td></tr>
			<tr><td class=\"left_column\">SSLKEY:</td><td>$this->sslkey</td></tr>
			<tr><td class=\"left_column\">CAINFO:</td><td>$this->cainfo</td></tr>
			<tr><td class=\"left_column\">COOKIEFILE:</td><td>$this->cookiefile</td></tr>
			<tr><td class=\"left_column\">PROXY:</td><td>$this->proxy</td></tr>
			<tr><td class=\"left_column\">VERBOSE:</td><td>$this->verbose</td></tr>
			<tr><td class=\"left_column\">INTERFACE:</td><td>$this->interface</td></tr>
			<tr><td class=\"left_column\">TIMEOUT:</td><td>$this->timeout</td></tr>
			<tr><td class=\"left_column\">CONNECT_TIME:</td><td>$this->connect_time</td></tr>		
			<tr><td class=\"left_column\">ENCODING:</td><td>$this->encoding</td></tr>
			<tr><td class=\"left_column\">SET-COOKIE:</td><td>$this->cookie</td></tr>
			<tr><td colspan=\"2\" class=\"left_column caption\">HTTP HEADER</td></tr>
			<tr class=\"caption_column\"><td class=\"left_column\">Параметры</td><td>Значения</td></tr><tr>";
			
			foreach ($this->httpheader as $key => $value) {
				echo "<tr><td class=\"left_column\">".strtoupper($key).":</td><td>$value</td></tr>";
			}
			
			echo "<tr><td colspan=\"2\" class=\"left_column caption\">STATISTICS CONNECT</td></tr>
			<tr class=\"caption_column\"><td class=\"left_column\">Параметры</td><td>Значения</td></tr><tr>";
			
			foreach (curl_getinfo($this->ch) as $key => $value) {
				echo "<tr><td class=\"left_column\">".strtoupper($key).":</td><td>$value</td></tr>";
			}
		
			echo "</table>
			</body></html>";
	}
	
	function close (){
		curl_close($this->ch);
	}
}
?>