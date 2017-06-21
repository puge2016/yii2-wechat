<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 17:44
 */

namespace frontend\controllers;

use Yii ;
use yii\web\Controller;
use common\util\Api ;
use common\util\Wechat ;
use common\util\Code ;

use common\models\Clock ;

class CronController extends Controller
{
    /**
     * @param int $max 每次从MQ获取的最大数量,也是推送给AWS的最大数量
     * @param int $type 1 一次推送一条，0 一次推送最大数量 $max
     */
    public function actionMqToAws($max=1, $type=1)
    {
        $api                = new Api();
        $mq                 = $api->activeMq();
        $q                  = Yii::$app->params['MQ']['QUEUE']  ;
        $api                = new Api() ;
        $redis              = $api->redis() ;
        $push               = array();
        $weChat             = new Wechat();
        for ($i=0; $i<$max; $i++) {
            $msg            = $mq->readFrame($q) ;
            $weChat->getLogs($msg) ;
            if ($msg) {
                $mqMsg      = $msg->body ;
                //$mqId       = $msg->headers['message-id'] ;
                $mqMsg      = json_decode($mqMsg, true) ;
                $userid     = $mqMsg['userid'] ;
                $time       = $mqMsg['time'] ;
                $redisKey   = $userid.':check_'.$time ;
                $uClock     = $redis->hGetAll($redisKey) ;
                if ($type == 1) {
                    // do $uClock

                } else {
                    $push[] = $uClock ;
                }
                //$mq->ack($msg) ; //消费
            } else {
                $err        = $mq->getErr() ;
                $weChat->getLogs($err) ;
                break ;
            }
        }

        if ($type == 0) {
            var_dump($push) ;
            // do $push
        }
        $mq->close() ;
    }

    /**
     * @param string $date string 日期
     * @param string $type string 类型 check:打卡数据， log:消息
     * 每天REDIS记录的打卡数据，存入数据库
     */
    public function actionRedisToSql($date='2017-05-07', $type='check')
    {
        $date               = $date ? $date : date('Y-m-d', time()) ;
        $cache              = Yii::$app->getCache() ;
        // 获取所有成员ID
        //$userList           = S('userlist') ;
        $userList           = $cache->get('userlist') ;
        $api                = new Api() ;
        $clockChat          = new Clock() ;
        $redis              = $api->redis() ;

        foreach ($userList as $userid) {
            $redis_date_id  = $userid.':'.$type.'_'.$date ; //qiumu:check_2017-05-14
            //获取成员打卡ID列表中所有的值
            $list           = $redis->lRange($redis_date_id, 0, -1) ;
            $isEmptyList    = empty($list) ;
            if ($isEmptyList) {
                continue ;
            } else {
                foreach ($list as $val) {
                    $redisKey_time              = $userid.':'.$type.'_'.$val ;
                    $userInfo[$userid][$val]    = $redis->hGetAll($redisKey_time) ;
                    switch ($type) {
                        case 'check' :
                            //打卡数据处理
                            $userInfo[$userid][$val]['userid']        = $userid ;
                            $userInfo[$userid][$val]['createtime']    = $val ;
                            $userInfo[$userid][$val]['wedate']        = $date ;
                            $clockChat->updateClock($userInfo[$userid][$val]) ;
                            break;
                        case 'log' :
                            //消息数据处理
                            break;
                    }
                }
            }
        }
    }

    /**
     * @param array $to touser/toparty/totag
     * @param string $content
     * @param int $type 2 一条 1,带链接
     * 发送打卡通知信息给成员
     */
    public function actionSendMess(array $to, $content='', $type=2)
    {
        $weChat     = new Wechat();
        $api        = new Api();
        $clockTime  = time();
        $touser     = isset($to['touser']) ? $to['touser'] : 'qiumu' ;
        $toparty    = isset($to['toparty']) ? $to['toparty'] : '' ;
        $totag      = isset($to['totag']) ? $to['totag'] : '' ;
        $agentid    = Yii::$app->params['YY23']['AgentID'] ;
        $content    = $content ?:'早上好打个卡！告诉我你的梦想是什么！';
        $safe       = '0';
        $varray     = array($touser, $toparty, $totag, $agentid, $content, $safe);
        $sendinfo   = $weChat->sendMessage($varray) ;
        if ($sendinfo === false ) {
            $err    = Code::getErr();
            Yii::info($err, 'wechat') ;
        } else {
            $clockLogs['content']   = $content ;
            $clockLogs['type']      = $type ; // 通知消息
            $clockLogs['userid']    = $touser ;
            $clockLogs['time']      = $clockTime ;
            $inRedis                = $api->RedisInsertCheck($clockLogs, 'log');
            if ($inRedis) {
                $cache              = Yii::$app->getCache() ;
                $userInfo           = $cache->get($touser) ;
                $userInfo['notice'] += 1 ;
                $cache->set($touser, $userInfo) ;
            } else {
                echo 'error!' ;
            }
        }
    }

}