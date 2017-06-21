<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 14:19
 */

namespace common\util;

use Yii ;
use frontend\models\Dep ;
use frontend\models\ClockChat ;
use frontend\models\UserChat ;

class Api
{
    public $_redis ;
    public $_mq ;
    public $errcode ;
    protected $_code ;
    protected $next_id ;

    public function __construct()
    {
        $this->_code = new Code() ;
    }

    public function redis()
    {
        //$redis
        $config['port']     = Yii::$app->params['REDIS']['PORT'] ;
        $config['host']     = Yii::$app->params['REDIS']['HOST'] ;
        $config['auth']     = Yii::$app->params['REDIS']['AUTH'] ;
        $this->_redis       = Redis::getInstance($config, 0) ;
        return $this->_redis ;
    }

    public function activeMq()
    {
        $config                 = array(
            'password'          =>  Yii::$app->params['MQ']['PASSWORD'],
            'username'          =>  Yii::$app->params['MQ']['USERNAME'],
            'host'              =>  Yii::$app->params['MQ']['HOST'],
            'port'              =>  Yii::$app->params['MQ']['PORT']
        );
        $this->_mq              = ActiveMq::getInstance($config) ;
        return $this->_mq ;
    }

    /**
     *
     * 从办公易拉取部门列表
     */
    public function insertDepList()
    {
        $weChat         = new Wechat() ;
        $con            = $weChat->getBgyDep();
        $department_list= $con['data']['department_list'] ;
        $isEmpty        = empty($department_list) ;
        if (!$isEmpty) {
            $depChat        = new Dep() ;
            $insertData     = array() ;
            foreach ($department_list as $key_list => $val_list) {
                $insertData['cid']      = 1 ;
                $insertData['did']      = $val_list['department_id'] ;
                $insertData['parentid'] = $val_list['we_parentid'] ;
                $insertData['dname']    = $val_list['we_name'] ;
                $insertData['dsort']    = $val_list['rank'] ;
                $depChat->updateDep($insertData) ;
            }
        }
    }

    /**
     *
     * 从办公易拉取考勤信息
     */
    public function insertClocks()
    {
        set_time_limit(0);
        $weChat         = new Wechat() ;
        $clockChat      = new ClockChat();
        $yes            = true ;
        $insertData     = array() ;
        $date           = '2017-03-16' ;
        while ($yes) {
            $next_id = $this->next_id ;
            $con = $weChat->getBgyClock($date, $date, $next_id);
            $num = count($con['data']['list']);
            for ($i = 0; $i < $num; $i++) {
                $this->next_id                  = $con['data']['next_id'] ;
                $insertData['staff_id']         = $con['data']['list'][$i]['staff_id'];
                $insertData['checkin_time']     = $con['data']['list'][$i]['checkin_time'];
                $insertData['checkout_time']    = $con['data']['list'][$i]['checkout_time'];
                $insertData['wedate']           = $date ;
                $clockChat->updateClockChat($insertData) ;
            }
            if ($next_id == $con['data']['next_id']) {
                $yes = false;
            }
        }
    }

    /**
     *
     *从办公易拉取人员信息
     */
    public function insertUserList()
    {
        $weChat         = new Wechat() ;
        for ($i=1; $i<3; $i++) {
            $con            = $weChat->getBgyUserList($i);
            $staff_list     = $con['data']['staff_list'];
            $isEmpty        = empty($staff_list) ;
            $userChat       = new UserChat() ;
            $insertData     = array();
            if (!$isEmpty) {
                foreach ($staff_list as $key_list => $val_list) {
                    $insertData['password']     = '';
                    $insertData['staff_id']     = $val_list['staff_id'];
                    $insertData['avatar']       = $val_list['we_avatar'];
                    $insertData['department']   = $val_list['we_department'];
                    $insertData['gender']       = $val_list['we_gender'];
                    $insertData['mobile']       = $val_list['we_mobile'];
                    $insertData['name']         = $val_list['we_name'];
                    $insertData['position']     = $val_list['we_position'];
                    $insertData['status']       = $val_list['we_status'];
                    $insertData['userid']       = $val_list['we_userid'];
                    $insertData['weixinid']     = $val_list['we_weixinid'];
                    $userChat->updateUser($insertData) ;
                }
            }
        }
    }

    /**
     * @param $userid
     * @param $date
     * @param $type
     * @return mixed
     * 从Redis中获取人员当天所有信息
     */
    public function getRedisUcs($userid, $date, $type)
    {
        //$redis
        $redis              = $this->redis() ;
        $redisKey_date      = $userid.':'.$type.'_'.$date ;

        //获取列表中所有的值
        $list               = $redis->lRange($redisKey_date, 0, -1) ;
        $isEmptyList        = empty($list) ;
        if ($isEmptyList) {
            //如果是空数组
            return false ;
        } else {
//            $userSql            = D('UserChat') ;
//            $arrUser            = $userSql->field('staff_id')->where(array('userid' => $userid))->find();
//            $staff_id           = $arrUser['staff_id'] ;
            $userInfo           = '' ;
            foreach ($list as $val) {
                $redisKey_time      = $userid.':'.$type.'_'.$val ;
                $userInfo[$val]     = $redis->hGetAll($redisKey_time) ;
            }
            $listUser[$userid]      = $userInfo ;
            return $listUser ;
        }
    }


    /**
     * @param $data array 打卡数据
     * @param $type string 存储类别 check 为打卡数据 log 为消息数据
     * @return bool
     * 打卡入REDIS
     */
    public function RedisInsertCheck($data, $type)
    {
        $redis          = $this->redis() ;
        $redisTime      = $data['time'] ; //打卡时间
        $userid         = $data['userid'] ; //用户ID
        $date           = date('Y-m-d', $redisTime) ; //打卡日期
        $rd             = '' ;

        $keyRedis_date  = $userid.':'.$type.'_'.$date ; //日期ID
        $keyRedis_time  = $userid.':'.$type.'_'.$redisTime ; //时间ID

        switch ($type) {
            case 'check' :
                $arrRedis       = array(
                    'latitude'      => $data['latitude'] ,
                    'longitude'     => $data['longitude'] ,
                    'point_title'   => $data['point_title'] ,
                    'point_content' => $data['point_content'] ,
                    'wetype'        => $data['wetype']
                ) ;
                $rd             = $redis->hMset($keyRedis_time, $arrRedis) ;
                break ;
            case 'log' :
                //type:1 打卡消息，type:2 通知消息
                $arrRedis       = array(
                    'type'      => $data['type'] ,
                    'content'   => $data['content']
                ) ;
                $rd             = $redis->hMset($keyRedis_time, $arrRedis) ;
        }
        $rt                     = $redis->rPush($keyRedis_date, $redisTime) ; // 存储打卡时间ID

        if ($rt && $rd) {
            return true ;
        } else {
            return false ;
        }
    }

    /**
     * @param $userid string userid
     * @param $time string 时间戳
     * @param $type string 获取数据类型 check:打卡数据 log:消息数据
     */
    public function getRedisUc($userid, $time, $type)
    {
        $redis              =  $this->redis();
        $redisKey_time      = $userid.':'.$type.'_'.$time ;
        $redis->hGetAll($redisKey_time) ;
    }

    /**
     * 成员信息批量入缓存
     */
    public function userInCache()
    {
        $users          = new UserChat() ;
        $department     = array() ;
        //$userInfos   = $users->field('name,gender,department,avatar,userid,position,staff_id,userid')->select() ;
        $userInfos      = $users->find()->asArray()->select([
            'name',
            'gender',
            'department',
            'avatar',
            'userid',
            'position',
            'staff_id',
            'userid'
        ])->all() ;

        foreach ($userInfos as $keyInfos => $userInfo ) {
            $depInfo                = Dep::find()->asArray()->where('id in ('.$userInfo['department'].')')->select('dname')->all() ;
            $department[$keyInfos]  = '';
            foreach ($depInfo as $val_info) {
                $department[$keyInfos] .=  $val_info['dname'] .',' ;
            }
            $department[$keyInfos]  = rtrim($department[$keyInfos], ',') ;
            $userid                         = $userInfo['userid'] ;
            $staffinfo                      = Yii::$app->params['STAFF_INFO'] ;
            $staffinfo['id']                = $userInfo['staff_id']; //员工编号
            $staffinfo['we_avatar']         = $userInfo['avatar']; // 微信头像
            $staffinfo['we_name']           = $userInfo['name']; // 名字
            $staffinfo['we_gender']         = $userInfo['gender']; // 性别
            $staffinfo['we_position']       = $userInfo['position']; // 职位
            $staffinfo['we_department']     = $userInfo['department']; // 部门
            $staffinfo['department']        = $department[$keyInfos]; // 部门信息

            //存入缓存
            $cache                          = Yii::$app->getCache() ;
            $cache->set($userid, $staffinfo) ;
        }
    }

    /**
     * @return bool|mixed
     * 成员列表存入缓存
     */
    public function userListInCache()
    {
        $users          = new UserChat() ;
        $userInfos      = $users->find()->asArray()->select('userid')->all() ;
        $isEmpty        = empty($userInfos) ;
        if ($isEmpty) {
            return false ;
        } else {
            $userInfos      = array_column($userInfos, 'userid');
            $cache          = Yii::$app->getCache() ;
            $cache->set('userlist', $userInfos) ;
            return true ;
        }
    }

    /**
     *
     * 缓存初始化
     */
    public function testInit()
    {
        // 清空 memcache 缓存
        $clean = $this->cleanCache();
        if ($clean) {
            $cache          = Yii::$app->getCache() ;
            // 班次表入缓存
            $checkTt        = $this->mkCheckTt() ;
            $times_id       = $checkTt['times_id'] ;
            $cache->set('times_id:'.$times_id , $checkTt) ;

            // 休息方案入缓存
            $rest           = $this->mkRestDay() ;
            $cache->set('rest_id:15271', $rest) ;

            // GPS入缓存
            $gps            = $this->mkCheckGps() ;
            foreach ($gps as $gk => $gv) {
                $cache->set('gps_id:'.$gk, $gv) ;
            }

            // 成员列表存入缓存入缓存
            $ulic           = $this->userListInCache() ;
            if ($ulic === false ) {
                $this->errcode = 10006 ;
                return false ;
            }

            // 成员信息批量入缓存
            $this->userInCache() ;
            $this->errcode = 0 ;
            return true ;
        } else {
            $this->errcode = 10005 ;
            return false ;
        }

    }

    /**
     *
     * 打卡规则
     */
    public function mkCheckTt()
    {
        // times_id:21920
        return Yii::$app->params['TIMES'] ; // C('TIMES') ;
    }

    /**
     * 打卡坐标地点距离
     */
    public function mkCheckGps()
    {
        return Yii::$app->params['GPSS'] ;
    }

    /**
     * @return array
     * 休息方案
     */
    private function mkRestDay()
    {
        // restdays_id:15271

        $week[] = 6 ;
        $week[] = 0  ;

        $data['off']['title'][] = '元旦';
        $data['off']['date'][] = '2017/1/1';
        $data['off']['title'][] = '元旦';
        $data['off']['date'][] = '2017/1/2';
        $data['off']['title'][] = '春节';
        $data['off']['date'][] = '2017/1/27';
        $data['off']['title'][] = '春节';
        $data['off']['date'][] = '2017/1/28';
        $data['off']['title'][] = '春节';
        $data['off']['date'][] = '2017/1/29';
        $data['off']['title'][] = '春节';
        $data['off']['date'][] = '2017/1/30';
        $data['off']['title'][] = '春节';
        $data['off']['date'][] = '2017/1/31';
        $data['off']['title'][] = '春节';
        $data['off']['date'][] = '2017/2/1';
        $data['off']['title'][] = '春节';
        $data['off']['date'][] = '2017/2/2';
        $data['off']['title'][] = '清明节';
        $data['off']['date'][] = '2017/4/2';
        $data['off']['title'][] = '清明节';
        $data['off']['date'][] = '2017/4/3';
        $data['off']['title'][] = '清明节';
        $data['off']['date'][] = '2017/4/4';
        $data['off']['title'][] = '劳动节';
        $data['off']['date'][] = '2017/4/29';
        $data['off']['title'][] = '劳动节';
        $data['off']['date'][] = '2017/4/30';
        $data['off']['title'][] = '劳动节';
        $data['off']['date'][] = '2017/5/1';
        $data['off']['title'][] = '端午节';
        $data['off']['date'][] = '2017/5/28';
        $data['off']['title'][] = '端午节';
        $data['off']['date'][] = '2017/5/29';
        $data['off']['title'][] = '端午节';
        $data['off']['date'][] = '2017/5/30';
        $data['off']['title'][] = '国庆节';
        $data['off']['date'][] = '2017/10/1';
        $data['off']['title'][] = '国庆节';
        $data['off']['date'][] = '2017/10/2';
        $data['off']['title'][] = '国庆节';
        $data['off']['date'][] = '2017/10/3';
        $data['off']['title'][] = '中秋节';
        $data['off']['date'][] = '2017/10/4';
        $data['off']['title'][] = '国庆节';
        $data['off']['date'][] = '2017/10/5';
        $data['off']['title'][] = '国庆节';
        $data['off']['date'][] = '2017/10/6';
        $data['off']['title'][] = '国庆节';
        $data['off']['date'][] = '2017/10/7';
        $data['off']['title'][] = '国庆节';
        $data['off']['date'][] = '2017/10/8';
        $data['on']['title'][] = '春节补班';
        $data['on']['date'][] = '2017/1/22';
        $data['on']['title'][] = '春节补班';
        $data['on']['date'][] = '2017/2/4';
        $data['on']['title'][] = '清明补班';
        $data['on']['date'][] = '2017/4/1';
        $data['on']['title'][] = '端午补班';
        $data['on']['date'][] = '2017/5/27';
        $data['on']['title'][] = '国庆补班';
        $data['on']['date'][] = '2017/9/30';

        $weekRest               = array();
        $dateRest               = array();
        $titleRest              = array();

        foreach ($week as $val_w) {
            $weekRest[$val_w]   = 0 ;
        }

        //一周七天，从周一到周日是否需要上班
        for ($i=0; $i<7 ; $i++) {
            $weekRest[$i]       = isset($weekRest[$i]) ? $weekRest[$i] : 1 ;
        }

        foreach ($data['on']['date'] as $key_on => $val_on) {
            $time               = strtotime($val_on) ;
            $dateRest[$time]    = 1 ;
            $titleRest[$time]   = $data['on']['title'][$key_on] ;
        }

        foreach ($data['off']['date'] as $key_off => $val_off) {
            $time               = strtotime($val_off) ;
            $dateRest[$time]    = 0 ;
            $titleRest[$time]   = $data['off']['title'][$key_off] ;
        }

        $mon                    = 12 ;
        $year                   = date('Y', time()) ; //2017年

        for ($i=1;$i<=$mon; $i++) {
            $d                  = cal_days_in_month(CAL_GREGORIAN, $i, $year);
            for ($it=1; $it<=$d; $it++) {
                $mkTime         = strtotime($year.'-'.$i.'-'.$it) ;
                $w              = date("w", $mkTime) ;
                $isRest         = isset($dateRest[$mkTime]) ;
                $weekTitle      = mb_substr( "日一二三四五六",date("w", $mkTime),1,"utf-8" ) ;
                $dateTitle      = date('m月d日', $mkTime) ;
                if (!$isRest) {
                    //如果未设置
                    if ($weekRest[$w]) {
                        $dateRest[$mkTime]      = 1 ;
                        $titleRest[$mkTime]     = $dateTitle.'周'. $weekTitle . '上班' ;
                    } else {
                        $dateRest[$mkTime]      = 0 ;
                        $titleRest[$mkTime]     = $dateTitle.'周'. $weekTitle . '休息' ;
                    }
                } else {
                    $titleRest[$mkTime]         = $dateTitle.'周'. $weekTitle . $titleRest[$mkTime] ;
                }
            }
        }

        ksort($dateRest) ;
        ksort($titleRest) ;

        return array(
            'dateRest'  => $dateRest ,
            'titleRest'  => $titleRest
        );
    }

    /**
     * @param $userid
     * @return mixed
     * 从缓存中获取人员信息
     */
    public function getUserFromCache($userid)
    {
        $cache      = Yii::$app->getCache() ;
        $userinfo   = $cache->get($userid) ;
        return $userinfo ;
    }

    /**
     * 调用aws系统接口 get提交
     * @param $set
     * @param null $data 业务参数
     * @return bool|mixed
     */
    public function getAws($set, $data=null)
    {
        $cmd            = isset($set['cmd']) ? $set['cmd'] : '' ;
        $access_key     = isset($set['aws_key']) ? $set['aws_key'] : '' ;
        $secret         = isset($set['aws_secret']) ? $set['aws_secret'] : '' ;
        $request_url    = isset($set['aws_url']) ? $set['aws_url'] : '' ;


        if (!$cmd || !$access_key || !$secret || !$request_url) {
            $this->_code->setErr(10000, __METHOD__, __LINE__, '设置参数有误') ;
            return false ;
        }


        //当前时间戳 毫秒级
        $timestamp = round(microtime(true)*1000);
        $handle_data = array(
            'access_key'    => $access_key,
            'cmd'           => $cmd,
            'timestamp'     => $timestamp,
            'sig_method'    => 'HmacMD5',
            'format'        => 'json'
        );
        //合并业务参数
        if($data){
            $handle_data = array_merge($handle_data,$data);
        }
        //升序排序
        ksort($handle_data);
        //转换字符串,构造签名串
        $code = $secret;
        foreach($handle_data as $k => $v){
            $code .= $k . $v;
        }
        //生成HMACMD5签名
        $sig = strtoupper(hash_hmac('md5',$code,$secret));
        $handle_data['sig'] = $sig;
        //组织请求url
        $url = $request_url .'?'. http_build_query($handle_data);
        $result = json_decode(file_get_contents($url),true);
        return $result;
    }

    /**
     * aws接口请求方法 post提交数据
     * @param $data
     * @param array $set
     * @return bool
     */
    public function postAws($data, $set = array())
    {
        $isEmpty        = empty($set) ;
        if ($isEmpty) {
            $this->_code->setErr(10000, __METHOD__, __LINE__, '设置变量为空') ;
            return false ;
        } else {
            $cmd            = isset($set['cmd']) ? $set['cmd'] : '' ;
            $access_key     = isset($set['aws_key']) ? $set['aws_key'] : '' ;
            $secret         = isset($set['aws_secret']) ? $set['aws_secret'] : '' ;
            $request_url    = isset($set['aws_url']) ? $set['aws_url'] : '' ;

            if (!$cmd || !$access_key || !$secret || !$request_url) {
                $this->_code->setErr(10000, __METHOD__, __LINE__, '设置参数有误') ;
                return false ;
            }
            $timestamp      = round(microtime(true)*1000); //当前时间戳 毫秒级
            $json           = $data ; //生成json数据
            //构造签名串
            $code           = "{$secret}access_key{$access_key}cmd{$cmd}formatjsonsig_methodHmacMD5timestamp{$timestamp}zparams{$json}";
            //生成HMACMD5签名
            $sig            = strtoupper(hash_hmac('md5',$code,$secret));
            //生成HMACMD5签名
            $pass           = "timestamp={$timestamp}&sig_method=HmacMD5&cmd={$cmd}&access_key={$access_key}&format=json&sig={$sig}&zparams={$json}";
            $weChat         = new Wechat();
            $result         = $weChat->http_post($request_url, $pass) ;
            $result         = json_decode($result, true ) ;
            if($result['result']=='ok'){
                if(isset($result['data'])){
                    return $result['data'];
                } else {
                    return true;
                }
            }else{
                $errorCode      = $result['errorCode'] ;
                $msg            = $result['msg'] ;
                $this->_code->setErr($errorCode, __METHOD__, __LINE__, $msg) ;
                return false;
            }
        }
    }


    /**
     *
     * 清空缓存信息
     */
    public function cleanCache()
    {
        $cache          = Yii::$app->getCache() ;
        return $cache->flush() ;
    }

}