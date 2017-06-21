<?php
namespace common\lib\wechat ;


/**
 * XMLParse class
 *
 * 提供提取消息格式中的密文及生成回复消息格式的接口.
 */
class XMLParse
{

	/**
	 * 提取出xml数据包中的加密消息
	 * @param string $xmltext 待提取的xml字符串
	 * @return string 提取出的加密消息字符串
	 */
	public function extract($xmltext)
	{
		try {
			$xml = new \DOMDocument();
			$xml->loadXML($xmltext);
			$array_e = $xml->getElementsByTagName('Encrypt');
			$array_a = $xml->getElementsByTagName('ToUserName');
			$encrypt = $array_e->item(0)->nodeValue;
			$tousername = $array_a->item(0)->nodeValue;



			return array(0, $encrypt, $tousername);
		} catch (\Exception $e) {
			print $e . "\n";
			return array(ErrorCode::$ParseXmlError, null, null);
		}
	}

    /**
     * @param $encrypt string 加密后的消息密文
     * @param $signature string 安全签名
     * @param $timestamp string 时间戳
     * @param $nonce string 随机字符串
     * @return string
     * 生成xml消息
     */
	public function generate($encrypt, $signature, $timestamp, $nonce)
	{
		$format = "<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%s</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>";
		return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
	}
}


?>