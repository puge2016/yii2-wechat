<?php
namespace common\lib\wechat ;


class JSSDK {
  private $appId;
  private $appSecret;

  public function __construct($appId, $appSecret) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
  }

  public function getSignPackage() {
    $jsapiData = $this->getJsApiTicket();
    $jsapiTicket1 = $jsapiData['jsapi_ticket1'] ;
    $jsapiTicket2 = $jsapiData['jsapi_ticket2'] ;
    $group_id   = $jsapiData['group_id'] ;
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $timestamp = time();
    $nonceStr = $this->createNonceStr();
    $string1 = "jsapi_ticket=$jsapiTicket1&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
    $string2 = "group_ticket=$jsapiTicket2&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
    $signature1 = sha1($string1);
    $signature2 = sha1($string2);
    $signPackage = array(
      "appId"     => $this->appId,
      "group_id"  => $group_id,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature1" => $signature1,
      "signature2" => $signature2,
      "rawString1" => $string1,
      "rawString2" => $string2
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }
    /**
     * @string group_id
     * @return mixed
     */
    private function getJsApiTicket() {
        $data = json_decode($this->get_php_file("jsapi_ticket2.php"));
        if ($data->expire_time < time()) {
            // get from url
            $accessToken = $this->getAccessToken();
            $url1 = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url2 = "https://qyapi.weixin.qq.com/cgi-bin/ticket/get?access_token=$accessToken&type=contact";
            $res1 = json_decode($this->httpGet($url1));
            $res2 = json_decode($this->httpGet($url2));
            $JsApiData['jsapi_ticket1'] = $res1->ticket;
            $JsApiData['jsapi_ticket2'] = $res2->ticket;
            $JsApiData['group_id'] = $res2->group_id;
            if ($JsApiData['jsapi_ticket1'] && $JsApiData['jsapi_ticket2'] && $JsApiData['group_id'] ) {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket1 = $JsApiData['jsapi_ticket1'];
                $data->jsapi_ticket2 = $JsApiData['jsapi_ticket2'];
                $data->group_id = $JsApiData['group_id'];
                $this->set_php_file("jsapi_ticket2.php", json_encode($data));
            }
        } else {
            // get from file
            $JsApiData['jsapi_ticket1'] = $data->jsapi_ticket1;
            $JsApiData['jsapi_ticket2'] = $data->jsapi_ticket2;
            $JsApiData['group_id'] = $data->group_id;
        }
        return $JsApiData;
    }

  private function getAccessToken() {
    $data = json_decode($this->get_php_file("access_token2.php"));
    if ($data->expire_time < time()) {
      $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $res = json_decode($this->httpGet($url));
      $access_token = $res->access_token;

      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        $this->set_php_file("access_token2.php", json_encode($data));
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
  }
 
  private function httpGet($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$temp = curl_exec($ch);
	curl_close($ch);
	return $temp;
}

  private function get_php_file($filename) {
      $contents = trim(substr(file_get_contents($filename), 15)) ;
      return $contents ;
  }
  private function set_php_file($filename, $content) {
    $fp = fopen($filename, "w");
    fwrite($fp, "<?php exit();?>" . $content);
    fclose($fp);
  }
}

