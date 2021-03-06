<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6
 * Time: 16:23
 */
namespace frontend\modules\attend\controllers;

use Yii ;

use yii\web\NotFoundHttpException;
use yii\helpers\Json ;
use common\controllers\BaseController ;
use common\util\Wechat ;
use common\util\Code ;
use common\util\Api ;

class CheckController extends BaseController
{

    private $agentid ;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->agentid      = Yii::$app->params['YY23']['AgentID'] ;
    }

    /**
     *
     * 内勤打卡
     */
    public function actionYao()
    {
        $weChat = new Wechat();
        $islogin = $weChat->islogin() ;
        if ($islogin === false ) {
            $msg = Code::getErr() ;
            $weChat->getLogs($msg.':from '. __METHOD__ .' '. __LINE__) ;
            throw new NotFoundHttpException('The requested page does not exist.');
        } else {
            $signPackage = $weChat->getSignPackage('a');
            if ($signPackage === false) {
                $msg = Code::getErr() ;
                $weChat->getLogs($msg.':from '. __METHOD__ .' '. __LINE__) ;
                throw new NotFoundHttpException('The requested page does not exist.');
            } else {
                $request            = Yii::$app->getRequest();
                $isPost             = $request->getIsPost() ;
                if ($isPost) {
                    return $this->check() ;
                } else {
                    $session        = Yii::$app->getSession() ;
                    $userid         = $session->get('userid') ;
                    $userInfo       = $weChat->getSingleUsInCache($userid);
                    $gps_ids        = $userInfo['gps_ids'] ; //
                    $cache          = Yii::$app->getCache() ;

                    // 获取打卡GPS
                    foreach ($gps_ids as $gps_id) {
                        $htmlData['devicestr'][]    = $cache->get('gps_id:'.$gps_id) ;
                    }
                    $signPackage['userid']          = $userid ;
                    $htmlData['cdnUrl']             = 'http://' . $_SERVER['HTTP_HOST'] .'/' ;
                    $htmlData['siteServer']         = $htmlData['cdnUrl'] ;
                    $htmlData['jsVersion']          = 20170434 ;
                    $htmlData['modulejsVersion']    = 62 ;
                    $htmlData['baidu_ak']           = Yii::$app->params['BAIDU_AK'] ;
                    $htmlData['devicestr']          = json_encode($htmlData['devicestr']) ;
                    $session->set('step', 1) ;
                    return $this->renderPartial('yao', [
                        'signPackage'       => $signPackage,
                        'devicestr'         => $htmlData['devicestr'],
                        'cdnUrl'            => $htmlData['cdnUrl'],
                        'siteServer'        => $htmlData['siteServer'],
                        'jsVersion'         => $htmlData['jsVersion'],
                        'modulejsVersion'   => $htmlData['modulejsVersion'],
                        'baidu_ak'          => $htmlData['baidu_ak'],
                    ]);
                }
            }
        }
    }

    /**
     *
     * 外勤打卡
     */
    public function actionOutwork()
    {
        $weChat     = new Wechat();
        $islogin    = $weChat->islogin() ;
        $time       = time() ;
        if ($islogin === false ) {
            //错误记录
            $msg = Code::getErr() ;
            $weChat->getLogs($msg) ;
            throw new NotFoundHttpException('The requested page does not exist.');

        } else {
            $signPackage    = $weChat->getSignPackage('a') ;
            $request            = Yii::$app->getRequest() ;

            if ($signPackage === false) {
                //错误记录
                $msg = Code::getErr() ;
                $weChat->getLogs($msg) ;
                throw new NotFoundHttpException('The requested page does not exist.');

            } else {
                $isPost             = $request->getIsPost() ;
                if ($isPost) {
                    return $this->check() ;
                } else {
                    $lng            = $request->get('lng') ;
                    $lat            = $request->get('lat') ;
                    $point_title    = $request->get('point_title') ;
                    $point_content  = $request->get('point_content') ;

                    // 读取REDIS
                    $session        = Yii::$app->getSession() ;
                    $userid         = $session->get('userid') ;
                    $date           = date('Y-m-d', $time) ;
                    $type           = 'check' ;
                    $api            = new Api();
                    $redis          = $api->getRedisUcs($userid, $date, $type) ; // 查询打了几次卡
                    if ($redis === false ) {
                        $outWorkTimes               = 0 ;
                    } else {
                        $clockDatas                 = array_values($redis);
                        $outWorkTimes               = array_column($clockDatas[0], 'wetype');
                        $searchDatas                = array_count_values($outWorkTimes) ;
                        $outWorkTimes               = isset($searchDatas[1]) ? $searchDatas[1] : 0  ;
                    }

                    $lng_lat        = array(
                        'lng'           => $lng,
                        'lat'           => $lat,
                        'point_title'   => $point_title,
                        'point_content' => $point_content
                    );
                    $outTime        = date('H:i', $time) ;
                    $outTime        .= '<em>'. date('Y-m-d', $time) .'('. mb_substr( "日一二三四五六",date("w"),1,"utf-8" ) .')</em>';

                    $htmlData['userid']             = $session->get('userid');
                    $htmlData['cdnUrl']             = 'http://' .$_SERVER['HTTP_HOST'] .'/' ;
                    $htmlData['siteServer']         = $htmlData['cdnUrl'] ;
                    $htmlData['jsVersion']          = 20170428;
                    $htmlData['modulejsVersion']    = 52;
                    $htmlData['date_out']           = date('Y-m-d', $time) ;
                    $htmlData['outWorkTimes']       = $outWorkTimes ;
                    $htmlData['baidu_ak']           = Yii::$app->params['BAIDU_AK'] ;

                    return $this->renderPartial('outwork', [
                        'lng'               => $lng_lat['lng'],
                        'lat'               => $lng_lat['lat'],
                        'point_title'       => $lng_lat['point_title'],
                        'point_content'     => $lng_lat['point_content'],
                        'userid'            => $htmlData['userid'],
                        'cdnUrl'            => $htmlData['cdnUrl'],
                        'siteServer'        => $htmlData['siteServer'],
                        'jsVersion'         => $htmlData['jsVersion'],
                        'modulejsVersion'   => $htmlData['modulejsVersion'],
                        'date_out'          => $htmlData['date_out'],
                        'outWorkTimes'      => $htmlData['outWorkTimes'],
                        'baidu_ak'          => $htmlData['baidu_ak'],
                        'outTime'           => $outTime,
                        'signPackage'       => $signPackage,
                    ]);

                }
            }
        }
    }

    /**
     *
     * 当天数据在REDIS里面查询，历史数据在MYSQL里面查询
     */
    public function actionOutworkLog()
    {
        $weChat                     = new Wechat() ;
        $dataArr                    = array();
        $dataArr['data']['list']    = array() ;

        $request                    = Yii::$app->getRequest() ;
        $isPost                     = $request->getIsPost() ;
        $session                    = Yii::$app->getSession() ;

        if ($isPost) {
            $time           = time();
            $datetime       = $datetimeU = $request->post('datetime')?: $time  ;
            $datetime       = date('Y-m-d', $datetime) ;
            $today          = date('Y-m-d', $time) ;
            $getDb          = Yii::$app->getDb() ;


            if ($datetime == $today) {
                // 如果提交的日期是今天的日期
                // 当天数据从REDIS获取
                $nextDate   = 0 ;
                $userid                         = $session->get('userid') ;
                $type                           = 'check' ;
                $api                            = new Api() ;
                $redis                          = $api->getRedisUcs($userid, $today, $type) ;

                if ($redis !== false ) {
                    $clockDatas                 = array_values($redis) ;
                    foreach ($clockDatas[0] as $keyC => $valC) {
                        if ($valC['wetype']  == 1) {
                            // 如果是外勤打卡记录数据
                            $dataArr['data']['list'][]          = array(
                                'title'                         => $valC['point_title'] ,
                                'address'                       => $valC['point_content'] ,
                                'dec'                           => '',
                                'images'                        => array(),
                                'time'                          => date('H:i', $keyC),
                                'count'                         => 0
                            ) ;
                        }
                    }
                }

            } else {
                $conditionN     = "SELECT wedate FROM wechat_clock WHERE wedate > '{$datetime}' GROUP BY (wedate) ORDER BY wedate ASC LIMIT 1 ";
                $dataN          = $getDb->createCommand($conditionN)->queryAll() ;
                $nextDate       = isset($dataN[0]['wedate']) ? strtotime($dataN[0]['wedate'])  : strtotime($today) ;
            }
            $condition      = "SELECT wedate FROM wechat_clock WHERE wedate < '{$datetime}' GROUP BY (wedate) ORDER BY wedate DESC LIMIT 1 ";
            $conditionT     = "SELECT point_title,point_content,wedate,created_at FROM wechat_clock WHERE wedate = '{$datetime}' " ;
            $data           = $getDb->createCommand($condition)->queryAll() ;
            $dataT          = $getDb->createCommand($conditionT)->queryAll() ;

            $prevDate       = isset($data[0]['wedate'])  ? strtotime($data[0]['wedate'])  : 0 ;

            $dataArr['data']['currentDate']         = $datetime ;
            $dataArr['data']['currentWeek']         = mb_substr( "日一二三四五六",date("w", $datetimeU),1,"utf-8" ) ;
            $dataArr['data']['prevDate']            = $prevDate ;
            $dataArr['data']['nextDate']            = $nextDate ;
            $isEmptyT                               = empty($dataT) ;

            if (!$isEmptyT) {
                //如果数据库里面有数据
                foreach ($dataT as $valT) {
                    $dataArr['data']['list'][]          = array(
                        'title'                         => $valT['point_title'] ,
                        'address'                       => $valT['point_content'] ,
                        'dec'                           => '',
                        'images'                        => array(),
                        'time'                          => date('H:i', $valT['created_at']),
                        'count'                         => 0
                    ) ;
                }
            }

            $dataArr['errno']                       = 0 ;
            $dataArr['errmsg']                      = 'ok' ;
            $jsonData                               = json_encode($dataArr) ;
            return $jsonData ;
        } else {
            $signPackage                    = $weChat->getSignPackage('a') ;
            $signPackage['userid']          = $session->get('userid');
            $htmlData['cdnUrl']             = 'http://' . $_SERVER['HTTP_HOST'] .'/' ;
            $htmlData['siteServer']         = $htmlData['cdnUrl'] ;
            $htmlData['jsVersion']          = 20170428;
            $htmlData['modulejsVersion']    = 54;
            $htmlData['date_out']           = date('Y-m-d', time()) ;

            return $this->renderPartial(
                'outworklog',
                [
                    'signPackage'           => $signPackage,
                    'cdnUrl'                => $htmlData['cdnUrl'] ,
                    'siteServer'            => $htmlData['siteServer'] ,
                    'jsVersion'             => $htmlData['jsVersion'] ,
                    'modulejsVersion'       => $htmlData['modulejsVersion'] ,
                    'date_out'              => $htmlData['date_out'] ,
                ]
            );
        }
    }

    /**
     *
     * 外勤打卡——坐标微调
     */
    public function actionPanel()
    {
        $weChat     = new Wechat();
        $islogin    = $weChat->islogin() ;
        if ($islogin === false ) {
            $msg = Code::getErr() ;
            $weChat->getLogs($msg) ;
        } else {
            $signPackage    = $weChat->getSignPackage('a') ;
            $request        = Yii::$app->getRequest() ;
            $lng            = $request->get('lng') ;
            $lat            = $request->get('lat') ;
            $lng_lat        = array(
                'lng'       => $lng,
                'lat'       => $lat
            );

            $htmlData['cdnUrl']             = 'http://' . $_SERVER['HTTP_HOST'] .'/' ;
            $htmlData['siteServer']         = $htmlData['cdnUrl'] ;
            $htmlData['jsVersion']          = 20170428;
            $htmlData['modulejsVersion']    = 52;
            $htmlData['baidu_ak']           = Yii::$app->params['BAIDU_AK'] ;

            return $this->renderPartial(
                'panel',
                [
                    'lng'                   => $lng_lat['lng'],
                    'lat'                   => $lng_lat['lat'],
                    'cdnUrl'                => $htmlData['cdnUrl'] ,
                    'siteServer'            => $htmlData['siteServer'] ,
                    'jsVersion'             => $htmlData['jsVersion'] ,
                    'modulejsVersion'       => $htmlData['modulejsVersion'] ,
                    'baidu_ak'              => $htmlData['baidu_ak'] ,
                    'signPackage'           => $signPackage
                ]
            );
        }
        return '';
    }

    /**
     * 打卡信息入库
     */
    private function check()
    {
        $request    = Yii::$app->getRequest() ;
        $cache      = Yii::$app->getCache() ;
        $session    = Yii::$app->getSession() ;
        $weChat     = new Wechat() ;

        // 信息初始化
        $clockTime  = time();
        $session->set('clockTime', $clockTime);
        $touser     = $session->get('userid');
        $toparty    = '';
        $totag      = '';
        $agentid    = $this->agentid;
        $content    = '打卡时间:' . date('Y年m月d日 H点i分s秒', $clockTime);
        $safe       = '0';
        $varray     = array($touser, $toparty, $totag, $agentid, $content, $safe);

        // 打卡信息入REDIS
        $clockDatas = array();
        $clockDatas['latitude']     = $request->post('lat') ;
        $clockDatas['longitude']    = $request->post('lng') ;
        $clockDatas['point_title']  = $request->post('point_title') ;
        $clockDatas['point_content']= $request->post('point_content') ;
        $clockDatas['wetype']       = $request->post('wetype','0') ;
        $clockDatas['time']         = $clockTime ;
        $clockDatas['userid']       = $touser ;
        // 入REDIS
        $api                        = new Api();
        $insertClock                = $api->RedisInsertCheck($clockDatas, 'check') ;
        if ($insertClock) {
            // 入消息队列
            $mqData                 = array(
                'userid'            => $touser,
                'time'              => $clockTime
            );
            $mqData                 = json_encode($mqData) ;
            $mq                     = $api->activeMq() ;
            $queue                  = Yii::$app->params['MQ']['QUEUE'] ; // 队列名称
            $transaction_id         = 'ttc'; // 事务ID
            $headers                = array(
                'transaction' => $transaction_id
            ) ;
            $mq->begin($transaction_id) ;
            try{
                $mqRresult          = $mq->sendMessage($mqData, $queue, $headers) ; // 入队
                if ($mqRresult === false ) {
                    $errMsg             = $mq->getErr() ;
                    $weChat->getLogs($errMsg) ;
                }
                $mq->commit($transaction_id) ; // 提交mq事务
            } catch (\Exception $e) {
                $mq->abort($transaction_id); // mq事务回滚
                $weChat->getLogs($e->getMessage()) ;
            }
            $mq->close() ; // 关闭MQ
            $userInfo               = $cache->get($touser) ;

            // 用户如果设置发送消息则发送
            // 用户如果不允许发送消息则不发送
            if ($userInfo['remind_set']['type3']) {
                // 发送信息给用户
                $result                 = $weChat->sendMessage($varray) ;
                if ($result ===false ) {
                    // 发送失败记录失败信息
                    $msg = Code::getErr() ;
                    $weChat->getLogs($msg.':from '. __METHOD__ .' '. __LINE__) ;
                    return Json::encode(['errno'=>1]);
                } else {
                    // 发送成功记录发送消息
                    $clockLogs['content']   = $content ;
                    $clockLogs['type']      = 1 ;
                    $clockLogs['userid']    = $touser ;
                    $clockLogs['time']      = $clockTime ;
                    $api->RedisInsertCheck($clockLogs, 'log'); // 发送消息历史纪录存入REDIS
                    $userInfo['notice']     += 1 ; // 未阅读消息存入内存
                    $cache->set($touser, $userInfo);
                    return Json::encode(['errno'=>0]);
                }
            } else {
                return Json::encode(['errno'=>0]);
            }
        } else {
            return Json::encode(['errno'=>1]);
        }
    }

    /**
     * 打卡成功返回页面
     */
    public function actionWesuccess()
    {
        $weChat = new Wechat() ;
        $islogin = $weChat->islogin() ;
        if ($islogin === false ) {
            $msg = Code::getErr() ;
            $weChat->getLogs($msg) ;
        } else {
            $session        = Yii::$app->getSession() ;
            $step           = $session->get('step');
            if ( $step == 1 ) {
                $signPackage = $weChat->getSignPackage('a') ;
                if ($signPackage === false) {
                    $msg = Code::getErr() ;
                    $weChat->getLogs($msg) ;
                } else {
                    $signPackage['userid']          = $session->get('userid') ;
                    $htmlData['clockTime']          = date('Y-m-d H:i:s',  $session->get('clockTime')) ;
                    $htmlData['cdnUrl']             = 'http://' . $_SERVER['HTTP_HOST'] .'/' ;
                    $htmlData['siteServer']         = $htmlData['cdnUrl'] ;
                    $htmlData['jsVersion']          = 20170428 ;
                    $htmlData['modulejsVersion']    = 55 ;

                    return $this->renderPartial('wesuccess',[
                        'signPackage'               => $signPackage ,
                        'clockTime'                 => $htmlData['clockTime'] ,
                        'cdnUrl'                    => $htmlData['cdnUrl'] ,
                        'siteServer'                => $htmlData['siteServer'] ,
                        'jsVersion'                 => $htmlData['jsVersion'] ,
                        'modulejsVersion'           => $htmlData['modulejsVersion'] ,
                    ]);
                }
            } else {
                $session->set('step',1) ;
            }
        }
        return false ;
    }

    public function actionIsEarly()
    {
        $session        = Yii::$app->getSession() ;
        $userid         = $session->get('userid') ;
        $cache          = Yii::$app->getCache() ;
        $arrUser        = $cache->get($userid) ;
        $isEmptyU       = empty($arrUser);
        if (!$isEmptyU) {
            $time               = time() ;
            $date               = date('Y-m-d', $time) ;
            $times_id           = $arrUser['times_id'] ;
            $timesInfo          = $cache->get('times_id:'.$times_id) ;
            // 正常上班的时间
            $duTime             = ($timesInfo['food_end'] - $timesInfo['food_start'] + $timesInfo['duration']) * 60 ;

            $api                = new Api();
            $redis              = $api->redis();
            $redisKey_date      = $userid.':check_'. $date ;
            $list               = $redis->lRange($redisKey_date, 0, -1) ;
            $checkin_time       = isset($list[0]) ? $list[0] : 0;
            $timex              = $time - $checkin_time  ;
            //是否早退
            $isEarly            = ($timex > $duTime) ? 0 : 1 ;
            return Json::encode($isEarly);
        }
        return false ;
    }
}