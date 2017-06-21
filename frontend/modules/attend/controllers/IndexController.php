<?php
namespace frontend\modules\attend\controllers;

use yii\web\NotFoundHttpException;
use yii\helpers\Json ;
use common\util\Wechat ;
use common\util\Code ;
use common\util\Api ;
use common\controllers\BaseController ;
use common\models\Clock ;
use common\models\UserChat ;

class IndexController extends BaseController
{
    /**
     * 考勤首页
     */
    public function actionIndex()
    {
        $weChat = new Wechat();
        $islogin = $weChat->islogin() ;
        if ($islogin === false ) {
            $msg = Code::getErr() ;
            $weChat->getLogs($msg) ;
        } else {
            $signPackage    = $weChat->getSignPackage('a');
            $session        = \Yii::$app->getSession() ;
            $userId         = $session->get('userid');
            $cache          = \Yii::$app->getCache() ;
            $arrUser        = $cache->get($userId);
            $notice         = ($arrUser['notice'] > 9 ) ? '9+' :  $arrUser['notice'] ;
            $badge          = $notice ?  '<span class="weui-badge" style="position: absolute;top: -2px;right: -13px;">'.$notice.'</span>' : '';

            $htmlData['userid']             = $session->get('userid');
            $htmlData['cdnUrl']             = 'http://' . $_SERVER['HTTP_HOST'] .'/' ;
            $htmlData['siteServer']         = $htmlData['cdnUrl'] ;
            $htmlData['jsVersion']          = 20170428;
            $htmlData['modulejsVersion']    = 55;
            $htmlData['date_out']           = date('Y-m-d', time()) ;
            $htmlData['time']               = time();
            $htmlData['arrUser']            = $arrUser ;
            $htmlData['badge']              = $badge ;
            $htmlData['meta_title']         = '首页' ;
            $htmlData['signPackage']        = $signPackage ;

            return $this->renderPartial('index', [
               'userid'             =>$htmlData['userid'] ,
               'cdnUrl'             =>$htmlData['cdnUrl'] ,
               'siteServer'         =>$htmlData['siteServer'] ,
               'jsVersion'          =>$htmlData['jsVersion'] ,
               'modulejsVersion'    =>$htmlData['modulejsVersion'] ,
               'date_out'           =>$htmlData['date_out'] ,
               'time'               =>$htmlData['time'] ,
               'arrUser'            =>$htmlData['arrUser'] ,
               'badge'              =>$htmlData['badge'] ,
               'meta_title'         =>$htmlData['meta_title'] ,
               'signPackage'        =>$htmlData['signPackage'] ,
            ]);
        }
        return '' ;
    }

    public function actionRecord()
    {
        $request        = \Yii::$app->getRequest() ;
        $isPost         = $request->getIsPost() ;
        $session    = \Yii::$app->getSession() ;

        if ($isPost) {
            $date       = $request->post('date') ;
            $staffid    = $request->post('staffid') ;
            $userid     = $session->get('userid') ;
            $json       = $this->mkCheckJson($date, $staffid, $userid) ;
            if ($json === false ) {
                throw new NotFoundHttpException('The requested page does not exist.');
            } else {
                return Json::encode($json) ;
            }
        } else {
            $weChat = new Wechat();
            $signPackage = $weChat->getSignPackage('a');
            if ($signPackage === false) {
                $msg = Code::getErr() ;
                $weChat->getLogs($msg) ;
            } else {
                $signPackage['userid']          = $session->get('userid') ;
                $htmlData['cdnUrl']             = 'http://' . $_SERVER['HTTP_HOST'] .'/' ;
                $htmlData['siteServer']         = $htmlData['cdnUrl'] ;
                $htmlData['jsVersion']          = 20170429;
                $htmlData['modulejsVersion']    = 54;
                return $this->renderPartial('record',[
                    'cdnUrl'            => $htmlData['cdnUrl'] ,
                    'siteServer'        => $htmlData['siteServer'] ,
                    'jsVersion'         => $htmlData['jsVersion'] ,
                    'modulejsVersion'   => $htmlData['modulejsVersion'] ,
                    'signPackage'       => $signPackage ,
                ]);
            }
        }
        return '';
    }

    /**
     * @param $date
     * @param $staffid
     * @param $userid
     * @return array|bool
     * 格式化JSON数据
     */
    private function mkCheckJson($date, $staffid, $userid)
    {
        // 数据初始化
        $checkJson  = array();
        $time       = time() ;
        $today      = date('Y-n-j', $time) ;
        $ymd        = explode('-', $today) ;
        $ty         = $ymd[0] ; // 今年
        $tm         = $ymd[1] ; // 这个月
        $td         = $ymd[2] ; // 今天几号

        if ($date !='' && $staffid != '' && $staffid == $userid) {
            /**
             * 从内存获取该成员休息方案，如果没有获取到休息方案
             * 重新从接口获取该会员对应的休息方案ID，根据此ID获取休息方案
             */
            $cache                              = \Yii::$app->getCache() ;

            $userInfo                           = $cache->get($userid);
            $rest_id                            = $userInfo['rest_id'] ;
            $times_id                           = $userInfo['times_id'] ;
            $rest_key                           = 'rest_id:'.$rest_id ;
            $time_key                           = 'times_id:'.$times_id ;
            $times                              = $cache->get($time_key) ; // 班次规则
            $rest                               = $cache->get($rest_key) ; // 休息方案
            $dateRest                           = $rest['dateRest'] ;
            $api                                = new Api();
            $check_sql                          = new Clock() ;

            $yAndM                              = explode('-', $date) ;
            $year                               = $yAndM[0] ; // 年
            $month                              = $yAndM[1] ; // 月
            $check_list                         = array() ;
            $checkTimes                         = array() ;
            $checkType                          = array() ;
            $checkout_time                      = 0 ;
            $duration_time                      = 0 ;
            $checkout_type                      = 0 ;


            // 如果查询的月份是当前年月份,则最后一天为今天
            // 如果不是为当前月份的最后一天
            if ($tm == $month && $ty == $year) {
                $d                              = $td ;
            } else {
                $d                              = cal_days_in_month(CAL_GREGORIAN, $month, $year); //这个月有几天
            }

            for ($i = 1; $i <= $d; $i++ ) {
                $rest_d                         = strtotime($year.'-'.$month.'-'.$i) ;
                $check_list[$rest_d]            = array();
                // day_status: 1休息日 2正常上班
                $day_status                     = $dateRest[$rest_d] + 1  ;

                $check_date                     = date('Y-m-d', $rest_d) ;

                if ($tm == $month && $ty == $year) {
                    // 如果是查看当月从REDIS获取数据
                    // 如果查看非当月从MYSQL获取数据
                    $redis                      = $api->getRedisUcs($userid, $check_date, 'check') ;
                    if ($redis === false ) {
                        // 如果没有数据

                        $checkin_time                       = 0 ;
                        $checkout_time                      = 0 ;
                        $duration_time                      = 0 ;
                        $checkin_type                       = 0 ;
                        $checkout_type                      = 0 ;

                    } else {
                        // 如果有数据

                        $clockDatas             = array_values($redis) ;
                        foreach ($clockDatas[0] as $key_c => $val_c) {
                            $address                        = $val_c['point_title'].','.$val_c['point_content'] ;
                            $check_list[$rest_d][]          = date('H:i:s', $key_c ) .' (' . $address . ')' ;
                            $checkout_time                  = $key_c ;
                            $checkTimes[$rest_d][]          = $key_c ;
                            $checkout_type                  = $val_c['wetype'] ;
                            $checkType[$rest_d][]           = $val_c['wetype'] ;
                        }

                        // 如果开始时间等于结束时间，说明成员只打了一次卡
                        if ($checkTimes[$rest_d][0] == $checkout_time) {
                            $checkin_time       = $checkout_time ;
                            $checkout_time      = 0 ;
                            $checkin_type       = $checkout_type ;
                            $checkout_type      = 0 ;
                        } else {
                            $checkin_time       = $checkTimes[$rest_d][0] ;
                            $checkin_type       = $checkType[$rest_d][0] ;
                            $timex              =  ($times['food_end'] - $times['food_start']) * 60 ;//休息时间
                            $duration_time      = $checkout_time - $checkin_time - $timex ;
                        }
                    }

                } else {
                    $map                                = array('wedate' => $check_date ) ;
                    $field                              = [ 'point_title','point_content','created_at','wetype' ] ;
                    $order                              = 'created_at asc' ;
                    $dataList                           = $check_sql->find()->asArray()->select($field)->where($map)->orderBy($order)->all() ;
                    $isEmptyDataList                    = empty($dataList) ;
                    if ($isEmptyDataList) {
                        // 如果没有数据
                        $checkin_time                       = 0 ;
                        $checkout_time                      = 0 ;
                        $duration_time                      = 0 ;
                        $checkin_type                       = 0 ;
                        $checkout_type                      = 0 ;
                    } else {
                        // 如果有数据
                        foreach ($dataList as $val_c) {
                            $address                        = $val_c['point_title'].','.$val_c['point_content'] ;
                            $check_time                     = date('H:i:s', $val_c['created_at']) ;
                            $check_list[$rest_d][]          = $check_time .' (' . $address . ')' ;
                            $checkout_time                  = $val_c['created_at'] ;
                            $checkTimes[$rest_d][]          = $val_c['created_at'] ;
                            $checkout_type                  = $val_c['wetype'] ;
                            $checkType[$rest_d][]                    = $val_c['wetype'] ;
                        }

                        if ($checkTimes[$rest_d][0] == $checkout_time) {
                            $checkin_time       = $checkout_time ;
                            $checkout_time      = 0 ;
                            $checkin_type       = $checkout_type ;
                            $checkout_type      = 0 ;
                        } else {
                            $checkin_time       = $checkTimes[$rest_d][0] ;
                            $checkin_type       = $checkType[$rest_d][0] ;
                            $timex              =  ($times['food_end'] - $times['food_start']) * 60 ;//休息时间
                            $duration_time      = $checkout_time - $checkin_time - $timex ;
                        }

                    }
                }

                $checkJson['data']['approves']  = array() ;

                // 打卡GPS信息
                $checkJson['data']['reportlist'][$rest_d]    = array(
                    'day_status'        => $day_status ,
                    'auto_status'       => 0 ,
                    'check_list'        => $check_list[$rest_d] , //地点信息
                    'status'            => 1
                );

                // checkDay 打卡时间信息
                $checkJson['data']['checkDay'][]    = array(
                    'check_range_start'             => $rest_d ,
                    'check_range_end'               => $rest_d + 86400 ,
                    'staff_id'                      => $staffid ,
                    'times_id'                      => $times_id ,
                    'date'                          => $rest_d ,
                    'checkin_time'                  => $checkin_time, //
                    'checkin'                       => $times['checkin'] ,
                    'checkout'                      => $times['checkout'] ,
                    'duration'                      => $times['duration'] ,
                    'checkout_time'                 => $checkout_time, //
                    'late'                          => $times['late'],
                    'early'                         => $times['early'] ,
                    'absent'                        => 0,
                    'approves'                      => '',
                    'times'                         => '',
                    'day_status'                    => 1 ,
                    'duration_time'                 => $duration_time , //
                    'is_late'                       => 0 ,
                    'is_early'                      => 0 ,
                    'checkin_type'                  => $checkin_type , //
                    'checkout_type'                 => $checkout_type //
                ) ;

            }

            // 用户信息
            $checkJson['data']['staffinfo']         = array(
                'we_account_id'         => '',
                'we_avatar'             => $userInfo['we_avatar'],
                'we_name'               => $userInfo['we_name'],
                'we_gender'             => $userInfo['we_gender'],
                'we_position'           => $userInfo['we_position'],
                'id'                    => $userInfo['id'],
                'we_department'         => $userInfo['we_department'],
                'department'            => $userInfo['department']
            );

            $checkJson['errno']                     = 0 ;
            $checkJson['errmsg']                    = '获取成功' ;

            return $checkJson ;

        } else {
            return false ;
        }
    }

    public function actionMessageinfo()
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
                $session                        = \Yii::$app->getSession() ;
                $cache                          = \Yii::$app->getCache() ;
                $userid                         = $session->get('userid') ;
                $signPackage['userid']          = $userid ;
                $htmlData['cdnUrl']             = 'http://' . $_SERVER['HTTP_HOST'] .'/' ;
                $htmlData['siteServer']         = $htmlData['cdnUrl'] ;
                $htmlData['jsVersion']          = 20170430 ;
                $htmlData['modulejsVersion']    = 58 ;
                $userInfo                       = $cache->get($userid) ;
                $userInfo['notice']             = 0 ;
                $cache->set($userid, $userInfo) ;

                return $this->renderPartial('messageinfo', [
                    'cdnUrl'                    => $htmlData['cdnUrl'] ,
                    'siteServer'                => $htmlData['siteServer'] ,
                    'jsVersion'                 => $htmlData['jsVersion'] ,
                    'modulejsVersion'           => $htmlData['modulejsVersion'] ,
                    'signPackage'               => $signPackage ,
                ]);

            }
        }
        return '';
    }

    /**
     * 消息
     */
    public function actionMessageget()
    {
        $request    = \Yii::$app->getRequest();
        $page       = $request->get('page', 1);
        $session    = \Yii::$app->getSession() ;
        $userid     =  $session->get('userid') ;
        $json       = $this->mkMessJson($page, $userid) ;
        return Json::encode($json) ;
    }

    /**
     * @param int $page
     * @param $userid
     * @return array|bool
     * 格式化消息数据
     */
    private function mkMessJson($page=1, $userid)
    {
        // 数据初始化
        $checkJson  = array() ;
        $allLogs    = array() ;
        $n          = 10 ;
        $time       = time() ;

        if ($userid != '') {

            $api                                = new Api();
            $redis                              = $api->redis() ;
            $checkJson['data']                  = array();
            // 从REDIS取出30天的数据
            $todayTime                      = date('Y-m-d', $time) ;
            for ($i=0; $i<31; $i++) {
                $ttime                      = strtotime($todayTime) ;
                $ttime                      = $ttime - ($i*24*60*60) ;
                $fd                         = date('Y-m-d', $ttime) ;
                $log_date_key               = $userid.':log_'.$fd ;
                $logs                       = $redis->lRange($log_date_key, 0, -1) ;
                if ($logs) {
                    $allLogs                = array_merge($allLogs, $logs);
                }
            }
            rsort($allLogs);

            $min                            = ($page-1) * $n ;
            $max                            = $page * $n ;
            // 每次请求只拉取10条数据
            for ($si=$min; $si<$max; $si++) {
                $ltime                       = $allLogs[$si] ;
                $log_time_key               = $userid.':log_'.$ltime ;
                $logData                    = $redis->hGetAll($log_time_key) ;
                if ($logData['type'] == 1) {
                    // 打卡消息
                    $msg_type               = 'text' ;
                    $content                = array(
                        'content'           => $logData['content'],
                        'avatar'            => '/Public/statics/common/admin/statics/icons/square/check.png'
                    );
                } else {
                    // 通知消息
                    $msg_type               = 'news' ;
                    $articles[0]             = array(
                        'title'           => $logData['content'],
                        'description'     => $logData['content'],
                        'url'             => 'http://' . $_SERVER['HTTP_HOST']  .'/wei/index'
                    );
                    $content                = array(
                        'articles'          => $articles,
                        'avatar'            => '/Public/statics/common/admin/statics/icons/square/check.png'
                    );
                }
                $logTime                    = '周'. mb_substr( "日一二三四五六",date("w", $ltime),1,"utf-8" ) . date('H:i', $ltime) ;
                $checkJson['data'][]        = array(
                    array(
                        'msg_type'          => $msg_type ,
                        'content'           => $content ,
                        'time'              => $logTime
                    )
                );
            }

            $isEmptyData                    = empty($checkJson['data']) ;
            if (!$isEmptyData) {
                krsort($checkJson['data']) ;
                $checkJson['data']          = array_values($checkJson['data']) ;
            }
            $checkJson['errno']             = 0 ;
            $checkJson['errmsg']            = 'ok' ;
            return $checkJson ;
        } else {
            return false ;
        }
    }

    /**
     * 在岗状态查询
     */
    public function actionStatus()
    {
        $weChat = new Wechat();
        $islogin = $weChat->islogin() ;
        if ($islogin === false ) {
            $msg = Code::getErr() ;
            return $weChat->getLogs($msg) ;
        } else {
            $session        = \Yii::$app->getSession() ;
            $userid         = $session->get('userid') ;
            if (!$userid) {
                throw new NotFoundHttpException('The requested page does not exist.') ;
            } else {
                $status                 = array() ;
                $cache                  = \Yii::$app->getCache() ;
                $userinfo               = $cache->get($userid) ;
                $status['isMe']         = 1 ;

                $request                = \Yii::$app->getRequest();
                $isPost                 = $request->getIsPost() ;
                if ($isPost) {
                    $search                 = $request->post('search') ;
                    if ($search) {
                        $userSql                = new UserChat();
                        $map                    = ['name' => $search] ;
                        $userFind               = $userSql->find()->asArray()->select('userid')->where($map)->one() ;
                        if ($userFind) {
                            if ($userid != $userFind['userid']) {
                                // 查询的人员不是自己
                                $userid                 = $userFind['userid'] ;
                                $userinfo               = $cache->get($userid) ;
                                $status['isMe']         = 0 ;
                            }
                        } else {
                            // NULL
                            $status['error']        = '391011!' ;
                            $status['status']       = '未查询到人员信息' ; // 员工状态保密
                            return Json::encode($status) ;
                        }
                    }
                    $status['error']        = 1 ;
                    $info['id']             = $userinfo['id'] ;
                    $info['we_avatar']      = $userinfo['we_avatar'] ;
                    $info['we_department']  = $userinfo['department'] ;
                    $info['we_gender']      = $userinfo['we_gender'] ;
                    $info['we_name']        = $userinfo['we_name'] ;
                    $info['we_postion']     = $userinfo['we_position'] ;
                    $status['info']         = $info ;

                    $date                   = date('Y-m-d',time());
                    $redis_id               = $userid.':check_'.$date ;
                    $api                    = new Api();
                    $redis                  = $api->redis() ;
                    $list                   = $redis->lRange($redis_id, 0, -1) ;
                    $start_time             = $redis->lRange($redis_id, 0, 0) ?: 0  ;
                    $end_time               = $redis->lRange($redis_id, -1, -1) ?: 0  ;
                    $diff_time              = $end_time[0] - $start_time[0] ;
                    $times_id               = 'times_id:'. $userinfo['times_id'] ;
                    $times                  = $cache->get($times_id) ;
                    $access_time            = $times['duration'] + $times['food_end'] - $times['food_start'] ;
                    $access_time            = $access_time * 60 ;
                    $isTimeOk               = ($diff_time - $access_time) >= 0 ; // 是否上够时间

                    $isEmpty                = empty($list) ;
                    // status 1 在岗, status 2 不在岗, status 3 保密，status 4 未知
                    // 查看有没有打卡，如果签到未签退为在岗，如果签到并签退同时上完规定时间班则不在岗
                    $status['status']       = $isEmpty  ? 2 : 1 ;
                    $status['status']       = $isTimeOk ? 2 : $status['status'] ;
                    $type                   = $request->post('type'); // 0 默认, 1 状态切换
                    if ($type == 1) {
                        if ($userinfo['status_set'] == 0 ) { // 如果原来关闭
                            $userinfo['status_set'] = 1 ; // 开启
                        } else { // 如果原来状态开启
                            $userinfo['status_set'] = 0 ; // 不允许别人看到
                            $status['status']       = 3 ; // 员工状态保密
                        }

                        if ($cache->set($userid, $userinfo)) { // 更新员工在岗状态 为保密
                            $status['errormsg']     = '状态切换成功' ; //
                        } else {
                            $status['errormsg']     = '状态切换失败' ; //
                        }
                    } else {
                        if ($userinfo['status_set'] == 0) {
                            $status['status']       = 3 ; // 员工状态保密
                        }
                    }
                    return Json::encode($status) ;
                } else {
                    $signPackage = $weChat->getSignPackage('a') ;
                    $htmlData['cdnUrl']             = 'http://' . $_SERVER['HTTP_HOST'] .'/' ;
                    $htmlData['siteServer']         = $htmlData['cdnUrl'] ;
                    $htmlData['jsVersion']          = 20170428 ;
                    $htmlData['modulejsVersion']    = 55 ;
                    $htmlData['status_set']         = $userinfo['status_set'] ;
                    return $this->renderPartial('status',[
                        'cdnUrl'                    => $htmlData['cdnUrl'],
                        'siteServer'                => $htmlData['siteServer'],
                        'jsVersion'                 => $htmlData['jsVersion'],
                        'modulejsVersion'           => $htmlData['modulejsVersion'],
                        'status_set'                => $htmlData['status_set'],
                        'signPackage'               => $signPackage,
                    ]);
                }
            }
        }
    }

}