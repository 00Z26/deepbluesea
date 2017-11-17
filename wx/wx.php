<?php
		$config = require('config.php');
		require_once "access_token.php";
		$access = new GETTOKEN();
		$access_token = $access->getToken();
		//var_dump($access_token);
		//var_dump($config);
		//定义常量
		//define(AppId,"wx79705a463101cc82");
		//define(AppSecret, "20fd20139a403efa40adf86c31fe7974");
		//define(access_token, "SnHmIZOYj6zu-h8LxixuhLdAEnm_TPOKA4zPRcqxyGXuMrI3eU7UWupmNbVSzsT3qoFjpEwlYvK23vwjo36qGgyLKcxVj4_4cV0LtyPspz6vFW0Z-5TWlaNW5cRCf7L4DDGiABARRU");

		//回复文本消息
		function reponseMsg($config){
		$postArr = file_get_contents("php://input");
		//var_dump ($postArr);
		$postObj = simplexml_load_string($postArr);
		//$postObj->ToUserName = '';
		//$postObj->FromUserName = '';
		//$postObj->CreateTime = '';
		//$postObj->MsgType = '';
		//$postObj->Event = '';
		$toUser = $postObj->FromUserName;
		$fromUser = $postObj->ToUserName;
		
		//回复关注
		if(strtolower($postObj->MsgType) == 'event'){
			//如果是subscribe 事件
			if(strtolower($postObj->Event == 'subscribe') ){
				//$toUser = $postObj->FromUserName;
				//$fromUser = $postObj->ToUserName;
				$time = time();
				$msgType = 'text';
				$content = $config['welcomeWord'];
				//var_dump($content);
				$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						</xml>";
				$info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
				echo $info;			
			}
		}
		
		//回复文本
		if($postObj->MsgType == 'text'){
				$time = time();
				$msgType = 'text';
				$content = $config['responseWord'];
				$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						</xml>";
				$reponseTxt = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
				echo $reponseTxt;			
		}
		//回复语音
		if ($postObj->MsgType == 'voice') {
			$time = time();
			$msgType = 'text';
			$voiceContent = $postObj->Recognition;
			$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						</xml>";
			$sayTxt = sprintf($template, $toUser, $fromUser, $time, $msgType, $voiceContent);
			echo $sayTxt;
		}
	}

	function createMenu($config,$access_token){
		$menuData = '{  
        "button":[
             {  
                  "type":"click",
                  "name":"图文消息",
                  "key":"1"
              },
              {
                  "name":"网页",
                  "sub_button":[
                    {
                       "type":"view",
                       "name":"自定义分享",
                       "url":"http://lwl.bingyan.net/courseTable.php"
                    },
                	]
              }]
         }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Errno'.curl_error($ch);
        }
        curl_close($ch);
	}


		//获得参数 signature nonce token timestamp echostr
		$nonce     = $_GET['nonce'];
		$token     = $config['firstToken'];
		//var_dump($token);
		$timestamp = $_GET['timestamp'];
		$echostr   = $_GET['echostr'];
		$signature = $_GET['signature'];
		//形成数组，然后按字典序排序
		$array = array();
		$array = array($nonce, $timestamp, $token);
		sort($array);
		//拼接成字符串,sha1加密 ，然后与signature进行校验
		$str = sha1( implode( $array ) );
		//生成菜单栏
		createMenu($config,$access_token);
		if( $str == $signature && $echostr ){
			//第一次接入weixin api接口的时候
			echo  $echostr;
			exit;
		} else {
			reponseMsg($config);
		}


	?>
	
