<?php
class GETTOKEN{

function getToken(){
	$config = require('config.php');
	$file = file_get_contents("access_token.json",true);
    $result = json_decode($file,true);
    $appid = $config['AppId'];
    $appsecret = $config['AppSecret'];
	if (time() > $result['expires']){
        $data = array();
        $data['access_token'] = $this->getNewToken($appid,$appsecret);
        $data['expires'] = time() + 7000;
        $jsonStr = json_encode($data);
        $fp = fopen("access_token.json", "w");
        fwrite($fp, $jsonStr);
        fclose($fp);
        return $data['access_token'];
    }else{
        return $result['access_token'];
    }
}

function getNewToken($appid,$appsecret){
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
    $access_token_Arr = $this->https_request($url);
    //var_dump($access_token_Arr);
    //var_dump($access_token_Arr->access_token);
    return $access_token_Arr->access_token;
}

function https_request ($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $out = curl_exec($ch);
        //var_dump(json_decode($out));
        curl_close($ch);
        return  json_decode($out);
}
}


?>