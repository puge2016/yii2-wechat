<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/9
 * Time: 12:40
 */

namespace backend\modules\wechat\controllers;

use common\controllers\BaseController ;
use common\util\Wechat;
use common\util\Code;

class AgentController extends BaseController
{
    public function actionIndex()
    {
        $weChat = new Wechat();
        $agentContent = $weChat->getAgentList();
        if ($agentContent === false ) {
            \Yii::info(Code::getErr(), 'wechat') ;
        } else {
            $this->renderPartial('index',[
                'agentContent'          => $agentContent
            ]);
        }
    }

    public function actionGetAgent()
    {
        $id = 114 ;
        $weChat = new Wechat() ;
        $agentInfo = $weChat->getAgentInfo($id) ;
        if ($agentInfo === false ) {
            \Yii::info(Code::getErr(), 'wechat') ;
        } else {
            \Yii::info($agentInfo, 'wechat') ;
        }
    }

    public function actionSetAgent()
    {
        $agentid = 104;
        $name = '测试应用';
        $desc = '测试应用描述';
        $rdomain = 'demo.lamp168.com';
        $homeUrl = 'http://www.qq.com';
        $chatUrl = 'http://www.baidu.com';
        $mediaid = '';

        $data = array(
            'agentid'               => $agentid,
            'report_location_flag'  => 0,
            'name'                  => $name,
            'description'           => $desc,
            'redirect_domain'       => $rdomain,
            'isreportuser'          => 0,
            'isreportenter'         => 0,
            'home_url'              => $homeUrl,
            'chat_extension_url'    => $chatUrl
        );

        if ($mediaid != '') {
            $data['logo_mediaid'] = $mediaid ;
        }

        $weChat = new Wechat();
        $data = $weChat::json_encode($data) ;

        $setCon = $weChat->setAgent($data) ;
        if ($setCon === false ) {
            \Yii::info(Code::getErr(), 'wechat') ;
        } else {
            \Yii::info($setCon, 'wechat') ;
        }
    }
}