<?php
class JSSDK{
public function getSignPackage() {	
			
		function curl_get_tickets($url){
    		$curl = curl_init(); // 启动一个CURL会话
    		curl_setopt($curl, CURLOPT_URL, $url);
    		curl_setopt($curl, CURLOPT_HEADER, 1);
    		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    		//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
    		$tmpInfo = curl_exec($curl);     //返回api的json对象
    		curl_close($curl);
    		$tmpInfo = strstr($tmpInfo, "{");
    		$jsonContent = json_decode($tmpInfo,true);
    		//var_dump($jsonContent);
    		$ticket = $jsonContent['ticket'];
    		//var_dump($ticket);
    		return $ticket;    
			}

		$config = require('config.php');
		require_once "access_token.php";
		$access = new GETTOKEN();
		$access_token = $access->getToken();
		//var_dump($access_token);
		//用php写签名算法的sha1的加密之后传值过来，查一下php怎么处理json
		$nonceStr = $config['nonceStr'];
		$time = time();
		$appid = $config['AppId'];
		//$access_token = 'SnHmIZOYj6zu-h8LxixuhLdAEnm_TPOKA4zPRcqxyGXuMrI3eU7UWupmNbVSzsT3qoFjpEwlYvK23vwjo36qGgyLKcxVj4_4cV0LtyPspz6vFW0Z-5TWlaNW5cRCf7L4DDGiABARRU';
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
		//var_dump($url);
		//获取当页网址
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$locationHtml = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		$jsTicket = curl_get_tickets($url);
		$string = "jsapi_ticket=$jsTicket&noncestr=$nonceStr&timestamp=$times&url=$locationHtml"; 
		$signature = sha1($string);
		//var_dump($signature);

		$signPackage = array(
	      "appId"     => $appid,
	      "nonceStr"  => $nonceStr,
	      "timestamp" => $time,
	      "signature" => $signature,
	      "rawString" => $string,
	      "url"       => $locationHtml,
	      "ticket"    => $jsTicket
	    );
    	return $signPackage;
	}
}

?>