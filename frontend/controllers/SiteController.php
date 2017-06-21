<?php
namespace frontend\controllers;

use Yii;

use common\lib\wechat\WXBizMsgCrypt ;
use common\controllers\BaseController ;
use common\util\Code ;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * Displays homepage.
     * @return mixed
     */
    public function actionIndex()
    {
        $token          = Yii::$app->params['YY23']['Token'] ;
        $encodingAesKey = Yii::$app->params['YY23']['EncodingAESKey'] ;
        $CorpID         = Yii::$app->params['WE_CORPID'] ;
        $sEchoStr       = Yii::$app->params['YY23']['ECHOSTR'] ;
        $sVerifyMsgSig      = isset($_GET['msg_signature']) ? $_GET['msg_signature'] : '';
        $sVerifyTimeStamp   = isset($_GET['timestamp']) ? $_GET['timestamp'] : '';
        $sVerifyNonce       = isset($_GET['nonce']) ? $_GET['nonce'] : '';
        $sVerifyEchoStr     = isset($_GET['echostr']) ? $_GET['echostr'] : '';

        $wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $CorpID);
        if ($sEchoStr === true) {
            Yii::info(113, 'wechat') ;

            // 验证用途
            $errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
            if ($errCode == 0) {
                // 获取正确
                // Yii::info($sEchoStr, 'wechat') ;
                return $sEchoStr ;
            } else {
                // 获取错误
                Yii::info(Code::getMsg($errCode), 'wechat') ;
                return $this->error(Code::getMsg($errCode));
            }
        } else {
            // 记录用途
            $sReqData = file_get_contents("php://input");
            Yii::info($sReqData, 'wechat') ;

            if ($sReqData) {
                $errCode = $wxcpt->DecryptMsg($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sReqData, $sMsg);
                if ($errCode == 0) {
                    $postObj        = simplexml_load_string($sMsg, 'SimpleXMLElement', LIBXML_NOCDATA);
                    $insertData     = json_encode($postObj);
                    Yii::info($insertData, 'wechat') ;
                    return true ;
                } else {
                    Yii::info(Code::getMsg($errCode), 'wechat') ;
                    return false ;
                }
            }
        }
        return false ;
    }

}
