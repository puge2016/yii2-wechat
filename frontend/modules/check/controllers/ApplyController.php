<?php
namespace frontend\modules\check\controllers;

use common\util\Wechat ;
use common\util\Code ;
use common\controllers\BaseController ;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/2
 * Time: 13:05
 */
class ApplyController extends BaseController
{
    public function actionMessageset()
    {
        $weChat = new Wechat();
        $islogin = $weChat->islogin() ;
        if ($islogin === false ) {
            $msg = Code::getErr() ;
            $weChat->getLogs($msg) ;
        } else {
            $signPackage = $weChat->getSignPackage('a');
            if ($signPackage === false) {
                $msg = Code::getErr() ;
                $weChat->getLogs($msg) ;
            } else {
                $session                       = \Yii::$app->getSession() ;
                $userid                         = $session->get('userid') ;
                $cache                          = \Yii::$app->getCache() ;
                $userInfo                       = $cache->get($userid) ;
                $remind_set                     = $userInfo['remind_set'] ;
                $signPackage['userid']          = $userid ;
                $htmlData['cdnUrl']             = 'http://' . $_SERVER['HTTP_HOST']  .'/' ;
                $htmlData['siteServer']         = $htmlData['cdnUrl'] ;
                $htmlData['jsVersion']          = 20170428 ;
                $htmlData['modulejsVersion']    = 55 ;

                return $this->renderPartial('messageset',[
                    'cdnUrl'                    => $htmlData['cdnUrl'],
                    'siteServer'                => $htmlData['siteServer'],
                    'jsVersion'                 => $htmlData['jsVersion'],
                    'modulejsVersion'           => $htmlData['modulejsVersion'],
                    'remind_set'                => $remind_set ,
                    'signPackage'               => $signPackage
                ]);
            }
        }
        return '';
    }

    public function actionReminder()
    {
        $request        = \Yii::$app->getRequest() ;
        $arrSet         = $request->post('set', '');
        $isEmpty        = empty($arrSet) ;
        if ($isEmpty) {
            return $this->error('提交数据有误!', 'attend/index/index') ;
        } else {
            $session                = \Yii::$app->getSession() ;
            $userid                 = $session->get('userid') ;
            $cache                  = \Yii::$app->getCache() ;
            $userInfo               = $cache->get($userid) ;
            $userInfo['remind_set'] = $arrSet ;
            $cache->set($userid, $userInfo) ;
            return $this->success('数据提交成功', 'attend/index/index') ;
        }
    }
}