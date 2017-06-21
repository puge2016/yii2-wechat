<?php
namespace common\util;

use Yii ;
use frontend\models\UserChat ;
use frontend\models\Dep;

defined('WECHAT_PATH') or define('WECHAT_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . '/runtime/logs/');
class Wechat
{
    protected $accessToken = array('a'=>'', 'h'=>'') ;
    protected $encodingAesKey ;
    protected $token ;
    protected $token_in ;
    protected $corpId ;
    protected $secret ;
    protected $userId ;
    protected $redirectUri ;
    protected $fetchChild ;
    protected $status ;
    protected $departmentId ;
    protected $code ;
    protected $agentId ;
    protected $mediaId ;
    protected $chatId ;
    protected $tagId ;
    protected $ticket = 'f32e27a5a3f80e060c427e2e84acb7d9'; // 办公易TICKET
    protected $_code ;
    protected $type ;
    protected $getDefUserInfo ; // 员工初始化信息

    public function __construct()
    {
        $this->encodingAesKey   = Yii::$app->params['YY23']['EncodingAESKey'] ;
        $this->token            = Yii::$app->params['YY23']['Token'] ;
        $this->corpId           = Yii::$app->params['WE_CORPID'] ;
        $this->secret           = Yii::$app->params['WE_SECRET'] ; // C('WE_SECRET') ; //
        $this->agentId          = Yii::$app->params['YY23']['AgentID'] ; // C('YY23.AgentID') ;
        $this->token_in         = Yii::$app->params['TOKEN_IN']  ; // 0 TOKEN信息存入缓存， 1 存入文
        $this->getDefUserInfo   = Yii::$app->params['STAFF_INFO']  ; //C('STAFF_INFO') ;
        $this->_code            = new Code();
    }

    /**
     * @param $tcode string when $tcode=a is normal , $tcode=h is conversion
     * @return array|bool
     * 获取 accesstoken
     */
    public function checkATK($tcode)
    {
        $tokens = $this->accessToken ;
        $isEmptyAccessToken = isset($tokens[$tcode]) ;
        if ($isEmptyAccessToken) {
            // 入缓存
            $accessToken = $this->getSaveData('access_token', $tcode);

            $hasErr = isset($accessToken['errcode']) ;
            if ($hasErr && $accessToken['errcode'] !=0) {
                $this->_code->setErr($accessToken['errcode'], __METHOD__, __LINE__) ; // 记录错误信息
                return false ;
            }
            $this->accessToken[$tcode] = $accessToken['access_token'] ;
            return $this->accessToken ;
        }
        return $this->accessToken ;
    }

    /**
     * @param $varray
     * @return bool|mixed
     * 给企业号成员发布信息
     */
    public function sendMessage($varray)
    {
        $accessToken = $this->checkATK('a');
        if ($accessToken === false ) {
            $this->_code->setErr(10000, __METHOD__, __LINE__);
            return false ;
        }
        $urls = $this->apiUrls() ;
        $isUrl = isset($urls['msg_send']);
        if (!$isUrl) {
            $this->_code->setErr(30009, __METHOD__, __LINE__);
            return false ;
        }

        // touser 成员ID列表（消息接收者，多个接收者用‘|’分隔，最多支持1000个）。特殊情况：指定为@all，则向关注该企业应用的全部成员发送
        // toparty 部门ID列表，多个接收者用‘|’分隔，最多支持100个。当touser为@all时忽略本参数
        // totag 标签ID列表，多个接收者用‘|’分隔，最多支持100个。当touser为@all时忽略本参数
        // msgtype 消息类型，此时固定为：text （支持消息型应用跟主页型应用）
        // agentid 企业应用的id，整型。可在应用的设置页面查看
        // content 消息内容，最长不超过2048个字节，注意：主页型应用推送的文本消息在微信端最多只显示20个字（包含中英文）
        // safe 表示是否是保密消息，0表示否，1表示是，默认0
        $jsonTpl    = '{"touser": "%s","toparty": "%s","totag": "%s","msgtype": "text","agentid": %s,"text": {"content": "%s"},"safe":%s}';
        $data       = vsprintf($jsonTpl, $varray);
        $result     = $this->http_post($urls['msg_send'], $data, false);
        if ($result) {
            $json   = json_decode($result, true) ;
            $hasErr = isset($json['errcode']) ;
            if ($hasErr && $json['errcode'] != 0 ) {
                $this->_code->setErr($json['errcode'], __METHOD__, __LINE__);
                return false ;
            }
            return $json ;
        }
        return false ;
    }

    /**
     * @param $tcode
     * @return array|bool
     * 获取企业号的JSSDK认证信息 a为通用认证， b为JSAPI的二次认证，涉及到企业会话需要
     */
    public function getSignPackage($tcode)
    {
        if ($tcode !='a' && $tcode != 'b') {
            $this->_code->setErr(10000, __METHOD__, __LINE__);
            return false ;
        }

        $accessToken = $this->checkATK('a');
        if ($accessToken === false) {
            return false ;
        }

        $num = ($tcode == 'a') ? 1 : 2 ;
        $arrTcode       = array('a','b');
        $ticketType     = array('jsapi_ticket', 'group_ticket');
        $signPackage    = false ;
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        for ($i=0; $i<$num; $i++ ) {
            $ticket = 'jsapi_ticket_'.$arrTcode[$i] ; // jsapi_ticket_a
            $jsapiData = $this->getSaveData($ticket, '') ;
            $errcode = isset($jsapiData['errcode']) ? $jsapiData['errcode'] : null ;
            if ($errcode == 0) {
                $jsapiTicket = $jsapiData['ticket'];
                $string[$i] = $ticketType[$i] . "=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
                $signature[$i] = sha1($string[$i]);
                $signPackage = array(
                    "appId"     => $this->corpId,
                    "nonceStr"  => $nonceStr,
                    "timestamp" => $timestamp,
                    "signature" => $signature,
                    "rawString" => $string,
                    "url"       => $url,
                );
                if ($arrTcode[$i] == 'b') {
                    $group_id   = $jsapiData['group_id'] ;
                    $signPackage['group_id'] = $group_id ;
                }
            } else {
                $this->_code->setErr($errcode, __METHOD__, __LINE__);
                return false;
            }
        }
        return $signPackage;
    }


    /**
     * @return bool
     * 判断是否是微信登录，并且必须是企业号里面的成员
     */
    public function islogin()
    {
        //微信认证地址
        $urlOauth = $this->oauth2();
        if ($urlOauth === false ) {
            // 判断地址是否正确
            return false ;
        } else {
            $isCode         = isset($_GET['code']) ;
            $session        = Yii::$app->getSession() ;
            $sessionUserId  = $session->get('userid') ;
            if ($isCode){
                $code = $_GET['code'] ;
                if (!$sessionUserId) {
                    $doUser = $this->doUser($code) ;
                    return $doUser ;
                } else {
                    return true ;
                }
            } else {
                if (!$sessionUserId) {
                    header('Location:'.$urlOauth);
                    exit('');
                } else {
                    return true ;
                }
            }
        }
    }

    /**
     * @param $code
     * @return bool
     * 获取企业号成员信息
     */
    public function doUser($code)
    {
        // UserId 和 DeviceId
        $content    = $this->getUserId($code);
        if ($content === false) {
            return false ;
        }
        $content    = json_decode($content, true) ;

        $hasErr     = isset($content['errcode']) ;
        $hasOpenId  = isset($content['OpenId']) ;
        if ($hasOpenId) {
            $this->_code->setErr(10001, __METHOD__, __LINE__);
            return false ;
        }
        if ($hasErr && $content['errcode'] !=0) {
            $this->_code->setErr($content['errcode'], __METHOD__, __LINE__);
            return false ;
        }
        $userID     = $content['UserId'] ;
        $deviceId   = $content['DeviceId'] ;
        if ($deviceId == '') {
            $this->_code->setErr(10002, __METHOD__, __LINE__);
            return false ;
        }
        $cache          = Yii::$app->getCache() ;
        $session        = Yii::$app->getSession() ;
        $userinfo       = $cache->get($userID) ;
        if ($userinfo) {
            // 已经缓存,直接返回TRUE
            $session->set('userid', $userID) ;
        } else {
            // 没有缓存，从微信获取数据
            $userCon    = $this->getUserInfo($userID);
            if ($userCon === false ) {
                return false ;
            }
            $department     = $userCon['department'] ;
            $dep            = '';
            foreach ($department as $val) {
                $dep        .= $val.',' ;
            }
            $dep            = rtrim($dep, ',');
            $extattr        = $userCon['extattr']['attrs'] ;
            $staff_id       = null ;
            foreach ($extattr as $val_ttr) {
                // 企业号人员必须有员工编码
                if ($val_ttr['name'] = 'staff_id') {
                    $staff_id   = $val_ttr['value'] ;
                    break;
                }
            }
            if ($staff_id) {
                $userDatas = array(
                    'staff_id'      => $staff_id,
                    'avatar'        => $userCon['avatar'],
                    'department'    => $dep ,
                    'gender'        => $userCon['gender'],
                    'mobile'        => isset($userCon['mobile']) ? $userCon['mobile'] : 0,
                    'name'          => $userCon['name'],
                    'position'      => '',
                    'status'        => $userCon['status'],
                    'userid'        => $userID
                );
                //入库
                $userChat       = new UserChat() ;
                if($userChat->updateUser($userDatas)) {
                    $depInfo                = Dep::find()->asArray()->where('id in ('.$dep.')')->select('dname')->all() ;
                    $department             = '';
                    foreach ($depInfo as $val_info) {
                        $department         .=  $val_info['dname'] .',' ;
                    }
                    $department                 = rtrim($department, ',') ;
                    $staffinfo                  = $this->getDefUserInfo() ;
                    $staffinfo['id']            = $staff_id ;
                    $staffinfo['we_avatar']     = $userCon['avatar'] ;
                    $staffinfo['we_name']       = $userCon['name'] ;
                    $staffinfo['we_gender']     = $userCon['gender'] ;
                    $staffinfo['we_position']   = '' ;
                    $staffinfo['we_department'] = $dep ;
                    $staffinfo['department']    = $department ;

                    $this->getLogs($staffinfo) ;
                    $cache                      = Yii::$app->getCache() ;
                    $session                    = Yii::$app->getSession() ;
                    //存入缓存
                    $cache->set($userID, $staffinfo) ;
                    //存入SESSION
                    $session->set('userid', $userID) ;
                } else {
                    $this->_code->setErr(10003, __METHOD__, __LINE__);
                    return false ;
                }
            } else {
                $this->_code->setErr(10004, __METHOD__, __LINE__);
                return false ;
            }
        }
        return true ;
    }

    public function getSingleUsInCache($userid)
    {
        // 从缓存获取数据，如果缓存没有数据从数据库获取
        if (!$userid) {
            $this->_code->setErr(10000, __METHOD__, __LINE__) ;
            return false ;
        } else {
            $cache              = Yii::$app->getCache() ;
            $userInfo           = $cache->get($userid) ;
            if ($userInfo === false ) {

                $userCon        = UserChat::find()->asArray()->where(['userid' => $userid])->all() ;
                if ($userCon) {
                    $dep            = $userCon['department'] ;
                    $depInfo        = Dep::find()->asArray()->where('id in ('.$dep.')')->select('dname')->all() ;
                    $department             = '';
                    foreach ($depInfo as $val_info) {
                        $department         .=  $val_info['dname'] .',' ;
                    }
                    $department                 = rtrim($department, ',') ;
                    $staffinfo                  = $this->getDefUserInfo() ;
                    $staffinfo['id']            = $userCon['staff_id'] ;
                    $staffinfo['we_avatar']     = $userCon['avatar'] ;
                    $staffinfo['we_name']       = $userCon['name'] ;
                    $staffinfo['we_gender']     = $userCon['gender'] ;
                    $staffinfo['we_position']   = '' ;
                    $staffinfo['we_department'] = $dep ;
                    $staffinfo['department']    = $department ;

                    //存入缓存
                    $cache->set($userid, $staffinfo) ;
                    return $staffinfo ;
                } else {
                    $this->_code->setErr(10000, __METHOD__, __LINE__) ;
                    return false ;
                }
            } else {
                return $userInfo ;
            }
        }

    }

    /**
     * @return string
     */
    public function oauth2()
    {
        $home_page          = ($this->is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/' ;
        $this->redirectUri  = $home_page . $_SERVER['REQUEST_URI'] ;
        $urls = $this->apiUrls();
        $isUrl = isset($urls['oauth']) ;
        if (!$isUrl) {
            $this->_code->setErr(30009, __METHOD__, __LINE__);
            return false ;
        }
        $oauthUrl = $urls['oauth'] ;
        return $oauthUrl ;
    }

    /**
     * @param $id
     * @return bool|mixed
     * 根据部门ID获取部门信息
     */
    public function getDepartment($id)
    {
        $this->departmentId = $id ;
        $accessToken = $this->checkATK('a');
        if ($accessToken === false) {
            return false ;
        }

        $url = $this->getUrls('department_get');
        if ($url === false) {
            return false ;
        }
        $depCon = $this->http_get($url);

        $depCon = json_decode($depCon, true);
        $hasErr = isset($depCon['errcode']);
        if ($hasErr && $depCon['errcode'] !=0) {
            $this->_code->setErr($depCon['errcode'], __METHOD__, __LINE__) ;
            return false ;
        }
        return $depCon ;
    }

    /**
     * @param $id
     * @return bool|mixed
     * 根据部门ID删除部门
     */
    public function delDepartment($id)
    {
        $this->departmentId = $id ;
        $accessToken = $this->checkATK('a');
        if ($accessToken === false) {
            return false ;
        }

        $url = $this->getUrls('department_delet');
        if ($url === false) {
            return false ;
        }
        $depCon = $this->http_get($url);
        $depCon = json_decode($depCon, true);
        $hasErr = isset($depCon['errcode']);
        if ($hasErr && $depCon['errcode'] !=0) {
            $this->_code->setErr($depCon['errcode'], __METHOD__, __LINE__);
            return false ;
        }
        return $depCon ;
    }

    /**
     * @param $data
     * @param string $do
     * @return bool|mixed|string
     * 更新创建部门
     */
    public function upCreDepartment($data, $do = 'update')
    {

        $accessToken = $this->checkATK('a');
        if ($accessToken ===false ) {
            return false ;
        }

        $url = $this->getUrls('department_'.$do);
        if ($url === false) {
            return false ;
        }

        $data = self::json_encode($data);
        $setCon = $this->http_post($url, $data) ;
        $setCon = json_decode($setCon, true) ;
        $hasErr = isset($setCon['errcode']) ;
        if ($hasErr && $setCon['errcode'] !=0) {
            $this->_code->setErr($setCon['errcode'], __METHOD__, __LINE__);
            return false ;
        }
        return $setCon ;

    }

    /**
     * @param $userid
     * @return bool|mixed
     * 根据成员ID获取成员信息
     */
    public function getUserInfo($userid)
    {
        $this->userId = $userid;
        $accessToken = $this->checkATK('a');
        if ($accessToken === false) {
            return false ;
        }

        $url = $this->getUrls('user_info');
        if ($url === false) {
            return false ;
        }

        $userIfoContent = $this->httpGet($url) ;
        $userIfoContent = json_decode($userIfoContent, true);
        $hasErr = isset($userIfoContent['errcode']);
        if ($hasErr && $userIfoContent['errcode'] !=0) {
            $this->_code->setErr($userIfoContent['errcode'], __METHOD__, __LINE__);
            return false ;
        }
        return $userIfoContent ;
    }

    /**
     * @param $code
     * @return bool|mixed
     * 根据code 获取成员ID
     */
    public function getUserId($code)
    {
        $this->code = $code ;
        $accessToken = $this->checkATK('a');
        if ($accessToken === false ) {
            return false ;
        }

        $urls = $this->apiUrls();
        $isUrl = isset($urls['user_getid']);
        if (!$isUrl) {
            $this->_code->setErr(30009, __METHOD__, __LINE__) ;
            return false ;
        }
        $userIdUrl = $urls['user_getid'] ;
        $userIdContent = $this->httpGet($userIdUrl) ;
        return $userIdContent ;
    }

    /**
     * @return bool|mixed
     * 获取微信企业号应用列表 ok
     */
    public function getAgentList()
    {
        $accessToken = $this->checkATK('a');
        if ($accessToken === false) {
            return false ;
        }

        $url = $this->getUrls('agent_list');
        if ($url === false) {
            return false ;
        }
        $agentContent = $this->httpGet($url);
        $agentContent = json_decode($agentContent, true);
        $hasErr = isset($agentContent['errcode']);
        if ($hasErr && $agentContent['errcode'] !=0) {
            $this->_code->setErr($agentContent['errcode'], __METHOD__, __LINE__) ;
            return false ;
        }
        return $agentContent ;
    }

    /**
     * @param $id
     * @return bool|mixed
     * 根据微信企业号应用ID获取该应用的信息 ok
     */
    public function getAgentInfo($id)
    {
        $this->agentId = $id ;
        $accessToken = $this->checkATK('a');
        if ($accessToken === false) {
            return false ;
        }

        $url = $this->getUrls('agent_get');
        if ($url === false) {
            return false ;
        }

        $agentInfo = $this->httpGet($url) ;
        $agentInfo = json_decode($agentInfo, true) ;
        $hasErr    = isset($agentInfo['errcode']) ;
        if ($hasErr && $agentInfo['errcode'] != 0) {
            $this->_code->setErr($agentInfo['errcode'], __METHOD__, __LINE__) ;
            return false ;
        }
        return $agentInfo ;
    }

    /**
     * @param $data
     * @return bool|mixed|string
     * 设置微信企业号应用 ok
     * {
    "agentid": 5,
    "report_location_flag": 0,
    "logo_mediaid": "xxxxx",
    "name": "NAME",
    "description": "DESC",
    "redirect_domain": "xxxxxx",
    "isreportenter":0,
    "home_url":"http://www.qq.com"
    }
     */
    public function setAgent($data)
    {
        $accessToken = $this->checkATK('a');
        if ($accessToken ===false ) {
            return false ;
        }

        $url = $this->getUrls('agent_set');
        if ($url === false) {
            return false ;
        }

        $setCon = $this->http_post($url, $data) ;
        $setCon = json_decode($setCon, true) ;
        $hasErr = isset($setCon['errcode']) ;
        if ($hasErr && $setCon['errcode'] !=0) {
            $errMsg         = isset($setCon['errmsg']) ? $setCon['errmsg'] : '' ;
            $this->_code->setErr($setCon['errcode'], __METHOD__, __LINE__, $errMsg) ;
            return false ;
        }
        return $setCon ;
    }

    /**
     * @param $depid
     * @param string $infcod 's'获取部门成员, 'x'获取部门成员详细信息
     * @param int $fetch_child 是否递归获取子部门成员
     * @param int $status 0,获取全部成员 1,获取已关注成员 2,获取禁用成员 4,获取未关注成员,未填写默认为4
     * @return bool|mixed
     */
    public function getUserList($depid, $infcod ='s', $fetch_child=1, $status=0 )
    {
        $this->departmentId = $depid;
        $this->fetchChild = $fetch_child;
        $this->status = $status;
        $accessToken = $this->checkATK('a');
        if ($accessToken === false) {
            return false ;
        }

        $url = $this->getUrls('user_list_'.$infcod);
        if ($url === false) {
            return false ;
        }

        $userListCon = $this->httpGet($url);
        $userListCon = json_decode($userListCon, true);
        $hasErr = isset($userListCon['errcode']);
        if ($hasErr && $userListCon['errcode'] !=0) {
            $this->_code->setErr($userListCon['errcode'], __METHOD__, __LINE__);
            return false ;
        }
        return $userListCon ;
    }

    /**
     * @param $data
     * @return bool|mixed|string
     * 批量删除成员
     */
    public function delMoreUser($data)
    {
        $accessToken = $this->checkATK('a');
        if ($accessToken ===false ) {
            return false ;
        }

        $url = $this->getUrls('user_delet_more');
        if ($url === false) {
            return false ;
        }

        $setCon = $this->http_post($url, $data) ;
        $setCon = json_decode($setCon, true) ;
        $hasErr = isset($setCon['errcode']) ;
        if ($hasErr && $setCon['errcode'] !=0) {
            $this->_code->setErr($setCon['errcode'], __METHOD__, __LINE__) ;
            return false ;
        }
        return $setCon ;
    }

    /**
     * @param $userid
     * @return bool|
     * 根据企业号成员ID删除成员
     */
    public function delUser($userid)
    {
        $this->userId = $userid ;
        $accessToken = $this->checkATK('a');
        if ($accessToken ===false ) {
            return false ;
        }

        $url = $this->getUrls('user_delet');
        if ($url === false) {
            return false ;
        }

        $userDel = $this->httpGet($url);
        $userDel = json_decode($userDel, true);
        $hasErr = isset($userDel['errcode']);
        if ($hasErr && $userDel['errcode'] !=0) {
            $this->_code->setErr($userDel['errcode'], __METHOD__, __LINE__) ;
            return false ;
        }
        return $userDel ;
    }

    /**
     * @param $data
     * @param string $do
     * @return bool|mixed|string
     * 创建或者更新企业号成员信息
     */
    public function upCreUser($data, $do='update' )
    {
        $accessToken = $this->checkATK('a');
        if ($accessToken ===false ) {
            return false ;
        }

        $url = $this->getUrls('user_'.$do);
        if ($url === false) {
            return false ;
        }

        $data = self::json_encode($data);
        $setCon = $this->http_post($url, $data) ;
        $setCon = json_decode($setCon, true) ;
        $hasErr = isset($setCon['errcode']) ;
        if ($hasErr && $setCon['errcode'] !=0) {
            $this->_code->setErr($setCon['errcode'], __METHOD__, __LINE__) ;
            return false ;
        }
        return $setCon ;
    }


    /**
     * @param int $page   页码
     * @return mixed
     * 获取办公易人员列表 ok
     */
    public function getBgyUserList($page=1)
    {
        //调用接口凭证
        $ticket             = $this->ticket ;
        //部门ID
        $we_id              = 0 ;
        //是否递归获取部门成员
        $fetch_child        = 0 ;
        $url                = 'https://qy.bangongyi.com/address/api/staff/list?';
        $url                .= 'ticket='.$ticket.'&we_id='.$we_id.'&fetch_child='.$fetch_child.'&page='.$page ;
        $con                = $this->httpGet($url) ;
        $con                = json_decode($con, true);
        return $con ;
    }

    /**
     * @param int $page
     * @return mixed
     * 获取办公易部门
     */
    public function getBgyDep($page=1)
    {
        //调用接口凭证
        $ticket             = $this->ticket ;
        $url                = 'https://qy.bangongyi.com/address/api/department/list?';
        $url                .= 'ticket='.$ticket.'&page='.$page ;
        $con                = $this->httpGet($url) ;
        $con                = json_decode($con, true);
        return $con ;
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param string $next_id
     * @return bool|mixed
     * 获取办公易打卡记录
     */
    public function getBgyClock($start_date, $end_date, $next_id ='')
    {
        $ticket             = $this->ticket ;
        $url                = 'https://qy.bangongyi.com/attend/api/check/day?';
        $url               .= 'ticket='.$ticket.'&start_date='.$start_date.'&end_date='.$end_date.'&next_id='.$next_id ;
        $con                = $this->http_get($url) ;
        $con                = json_decode($con, true) ;
        return $con ;
    }


    /**
     * @return bool|mixed
     * 获取企业号标签列表
     */
    public function  getTagList()
    {
        $accessToken = $this->checkATK('a');
        if ($accessToken === false) {
            return false ;
        }

        $url = $this->getUrls('tag_list');
        if ($url === false) {
            return false ;
        }
        $tagListCon = $this->httpGet($url);
        $tagListCon = json_decode($tagListCon, true);
        $hasErr = isset($tagListCon['errcode']);
        if ($hasErr && $tagListCon['errcode'] !=0) {
            $this->_code->setErr($tagListCon['errcode'], __METHOD__, __LINE__) ;
            return false ;
        }
        return $tagListCon ;
    }

    /**
     * @param $data
     * @param string $do
     * @return bool|mixed|string
     * 删除、增加企业号标签
     */
    public function delAddTagUser($data, $do='delet_user')
    {
        $accessToken = $this->checkATK('a');
        if ($accessToken ===false ) {
            return false ;
        }
        $url = $this->getUrls('tag_'.$do);
        if ($url === false) {
            return false ;
        }

        $data = self::json_encode($data);
        $setCon = $this->http_post($url, $data) ;
        $setCon = json_decode($setCon, true) ;
        $hasErr = isset($setCon['errcode']) ;
        if ($hasErr && $setCon['errcode'] !=0) {
            $this->_code->setErr($setCon['errcode'], __METHOD__, __LINE__) ;
            return false ;
        }
        return $setCon ;
    }

    /**
     * @param $tagid
     * @return bool|mixed
     * 根据标签ID获取标签下的成员
     */
    public function getTagUser($tagid)
    {
        $this->tagId = $tagid ;
        $accessToken = $this->checkATK('a');
        if ($accessToken === false) {
            return false ;
        }

        $url = $this->getUrls('tag_get');
        if ($url === false) {
            return false ;
        }

        $tagInfo = $this->httpGet($url) ;
        $tagInfo = json_decode($tagInfo, true) ;
        $hasErr    = isset($tagInfo['errcode']) ;
        if ($hasErr && $tagInfo['errcode'] != 0) {
            $this->_code->setErr($tagInfo['errcode'], __METHOD__, __LINE__);
            return false ;
        }
        return $tagInfo ;
    }

    /**
     * @param $tagid
     * @return bool|mixed
     * 根据标签ID删除标签
     */
    public function delTag($tagid)
    {
        $this->tagId = $tagid ;
        $accessToken = $this->checkATK('a');
        if ($accessToken === false) {
            return false ;
        }

        $url = $this->getUrls('tag_delet');
        if ($url === false) {
            return false ;
        }

        $tagInfo = $this->httpGet($url) ;
        $tagInfo = json_decode($tagInfo, true) ;
        $hasErr    = isset($tagInfo['errcode']) ;
        if ($hasErr && $tagInfo['errcode'] != 0) {
            $this->_code->setErr($tagInfo['errcode'], __METHOD__, __LINE__ ) ;
            return false ;
        }
        return $tagInfo ;
    }

    public function getDefUserInfo()
    {
        return $this->getDefUserInfo ;
    }

    public function getUrls($urlCode)
    {
        $urls = $this->apiUrls();
        $isUrl = isset($urls[$urlCode]);
        if (!$isUrl) {
            $this->_code->setErr(30009,__METHOD__, __LINE__) ;
            return false ;
        }
        $url = $urls[$urlCode] ;
        return $url ;
    }

    /**
     * @param $url
     * @return mixed
     */
    private function httpGet($url)
    {
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


    /**
     * @param $url string
     * @return bool|mixed
     * GET 请求
     */
    private function http_get($url)
    {
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    function http_post($url, $param, $post_file=false)
    {
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }


    /**
     * @param $p string  access_token
     * @param string $t a or h
     * @return array|mixed
     * 获取access_token, jsapi_ticket_a, jsapi_ticket_b, user ...
     * 如果有数据，提取数据，如果数据时间过期，重新生成数据
     */
    public function getSaveData($p, $t='')
    {
        $pt = ($t == '') ? $p : $p . '_' . $t ;
        $weContent = $this->get_php_file($pt) ;

        if ($weContent == '' ) {
            $res = $this->httpGetInFile($p, $t);
            return $res ;

        } else {
            $data = json_decode($weContent, true);
            if ($data['expire_time'] < time()) {
                $res = $this->httpGetInFile($p, $t);
                return $res ;
            } else {
                return $data ;
            }
        }
    }

    /**
     * @param $p
     * @param string $t
     * @return array|mixed
     * 从企业号API获取认证信息
     */
    private function httpGetInFile($p, $t='')
    {
        if ($t == '') {
            $pt = $p ;
        } else {
            $pt = $p . '_' . $t ;
            $this->secret = ($t == 'h') ?  Yii::$app->params['QYHH']['Secret']  : $this->secret ;
        }
        $urls = $this->apiUrls();
        $isUrl = isset($urls[$p]) ;
        if (!$isUrl) {
            $errData = array('errcode' => '30009');
            return $errData ;
        }
        $url = $urls[$p];

        $getData = $this->httpGet($url);
        $res = json_decode($getData, true);
        $hasErr = isset($res['errcode']) ;
        if ($hasErr && $res['errcode'] != 0 ) {
            return $res ;
        }
        $res['expire_time'] = time() + $res['expires_in'] - 200 ;
        $data = $res ;
        $data['in_file'] = 'ok';
        $this->set_php_file($pt, json_encode($data));
        return $res ;
    }

    private function get_php_file($p)
    {
        $type = $this->token_in ;

        if ($type == 1) {
            // 从文件中获取
            $fileName = $p . '.php';
            $weFile = WECHAT_PATH . $fileName ;
            $isFile = is_file($weFile);
            $contents = '' ;
            if ($isFile) {
                $contents = trim(substr(file_get_contents($weFile), 15)) ;
            } else {
                $this->set_php_file($p, $contents);
            }
        } else {
            // 从缓存中获取
            $id = $p . '_' . $this->agentId ;
            $cache      = Yii::$app->getCache() ;
            $contents   = $cache->get($id) ;
        }
        return $contents ;
    }
    /**
     * @param $p
     * @param $content string 存储的内容
     */
    private function set_php_file($p, $content)
    {
        $type = $this->token_in ;

        if ($type == 1) {
            // 自动创建日志目录
            $fileName = $p . '.php';
            $weFile = WECHAT_PATH . $fileName ;
            if (!is_dir(WECHAT_PATH)) {
                mkdir(WECHAT_PATH, 0755, true);
            }
            $fp = fopen($weFile, "w");
            fwrite($fp, "<?php exit();?>" . $content);
            fclose($fp);
        } else {
            $id = $p . '_' . $this->agentId ;
            $cache      = Yii::$app->getCache() ;
            $cache->set($id, $content) ;
        }
    }

    /**
     * @param $arr array
     * @return string
     * 微信api不支持中文转义的json结构
     */
    static function json_encode($arr)
    {
        $parts = array ();
        $is_list = false;
        //Find out if the given array is a numerical array
        $keys = array_keys ( $arr );
        $max_length = count ( $arr ) - 1;
        if (($keys [0] === 0) && ($keys [$max_length] === $max_length )) { //See if the first key is 0 and last key is length - 1
            $is_list = true;
            for($i = 0; $i < count ( $keys ); $i ++) { //See if each key correspondes to its position
                if ($i != $keys [$i]) { //A key fails at position check.
                    $is_list = false; //It is an associative array.
                    break;
                }
            }
        }
        foreach ( $arr as $key => $value ) {
            if (is_array ( $value )) { //Custom handling for arrays
                if ($is_list)
                    $parts [] = self::json_encode ( $value ); /* :RECURSION: */
                else
                    $parts [] = '"' . $key . '":' . self::json_encode ( $value ); /* :RECURSION: */
            } else {
                $str = '';
                if (! $is_list)
                    $str = '"' . $key . '":';
                //Custom handling for multiple data types
                if (!is_string ( $value ) && is_numeric ( $value ) && $value<2000000000)
                    $str .= $value; //Numbers
                elseif ($value === false)
                    $str .= 'false'; //The booleans
                elseif ($value === true)
                    $str .= 'true';
                else
                    $str .= '"' . addslashes ( $value ) . '"'; //All other things
                $parts [] = $str;
            }
        }
        $json = implode ( ',', $parts );
        if ($is_list)
            return '[' . $json . ']'; //Return numerical JSON
        return '{' . $json . '}'; //Return associative JSON
    }

    public function getLogs($data)
    {
        Yii::info($data, 'wechat') ;
    }

    public function postUrl($url, $data){

        $result = $this->http_post($url, $data);
        if ($result)
        {
            return $result ;
        }
        return false;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function is_ssl()
    {
        if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
            return true;
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }



    /**
     * @return mixed
     */
    private function apiUrls()
    {
        $urls = array();
        $urls['jsapi_ticket_a']  = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=" . $this->accessToken['a'] ;
        $urls['jsapi_ticket_b']  = "https://qyapi.weixin.qq.com/cgi-bin/ticket/get?access_token=" . $this->accessToken['h'] . "&type=contact" ;
        $urls['access_token']    = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid='. $this->corpId .'&corpsecret=' . $this->secret ;
        $urls_oauth_1            = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->corpId ;
        $urls_oauth_2            = '&redirect_uri=' . urlencode($this->redirectUri)  ;
        $urls_oauth_3            = '&response_type=code&scope=snsapi_base&state=corpid#wechat_redirect' ;
        $urls['oauth']           = $urls_oauth_1 . $urls_oauth_2 . $urls_oauth_3 ;
        $urls['auth_succ']       = 'https://qyapi.weixin.qq.com/cgi-bin/user/authsucc?access_token=' . $this->accessToken['a'] .'&userid='.$this->userId;
        $urls['msg_send']        = 'https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=' . $this->accessToken['a'] ;
        $urls['department_create'] = 'https://qyapi.weixin.qq.com/cgi-bin/department/create?access_token=' . $this->accessToken['a'] ;
        $urls['department_update'] = 'https://qyapi.weixin.qq.com/cgi-bin/department/update?access_token=' . $this->accessToken['a'] ;
        $urls['department_delet']  = 'https://qyapi.weixin.qq.com/cgi-bin/department/delete?access_token=' . $this->accessToken['a'] . '&id=' . $this->departmentId ;
        $urls['department_move']   = 'https://qyapi.weixin.qq.com/cgi-bin/department/move?access_token=' . $this->accessToken['a'] ;
        $urls['department_get'] = 'https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token=' . $this->accessToken['a'] .'&id=' . $this->departmentId;
        $urls['user_create']    = 'https://qyapi.weixin.qq.com/cgi-bin/user/create?access_token=' . $this->accessToken['a'] ;
        $urls['user_update']    = 'https://qyapi.weixin.qq.com/cgi-bin/user/update?access_token=' . $this->accessToken['a'] ;
        $urls['user_delet']     = 'https://qyapi.weixin.qq.com/cgi-bin/user/delete?access_token=' . $this->accessToken['a'] . '&userid=' . $this->userId ;
        $urls['user_delet_more']   = 'https://qyapi.weixin.qq.com/cgi-bin/user/batchdelete?access_token=' . $this->accessToken['a'] ;
        $urls['user_info']      = 'https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=' . $this->accessToken['a'] . '&userid=' . $this->userId ;
        $urls['user_list_s']    = 'https://qyapi.weixin.qq.com/cgi-bin/user/simplelist?access_token=' . $this->accessToken['a'] . '&fetch_child=' . $this->fetchChild . '&status='. $this->status ;
        $urls_user_list_x1      = 'https://qyapi.weixin.qq.com/cgi-bin/user/list?access_token=' ;
        $urls_user_list_x2      = $this->accessToken['a'] . '&department_id=' . $this->departmentId .'&fetch_child=' .$this->fetchChild. '&status=' . $this->status;
        $urls['user_list_x']    = $urls_user_list_x1 . $urls_user_list_x2 ;
        $urls['user_getid']     = 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=' . $this->accessToken['a'] . '&code='.$this->code.'&agentid='.$this->agentId ;
        $urls['user_invite']    = 'https://qyapi.weixin.qq.com/cgi-bin/invite/send?access_token=' . $this->accessToken['a'] ;
        $urls['tag_create']     = 'https://qyapi.weixin.qq.com/cgi-bin/tag/create?access_token=' . $this->accessToken['a'] ;
        $urls['tag_update']     = 'https://qyapi.weixin.qq.com/cgi-bin/tag/update?access_token=' . $this->accessToken['a'] ;
        $urls['tag_delet']      = 'https://qyapi.weixin.qq.com/cgi-bin/tag/delete?access_token=' . $this->accessToken['a'] . '&tagid=' . $this->tagId ;
        $urls['tag_get']        = 'https://qyapi.weixin.qq.com/cgi-bin/tag/get?access_token=' . $this->accessToken['a'] .'&tagid='.$this->tagId ;
        $urls['tag_add_user']   = 'https://qyapi.weixin.qq.com/cgi-bin/tag/addtagusers?access_token=' . $this->accessToken['a'] ;
        $urls['tag_delet_user'] = 'https://qyapi.weixin.qq.com/cgi-bin/tag/deltagusers?access_token=' . $this->accessToken['a'] ;
        $urls['tag_list']       = 'https://qyapi.weixin.qq.com/cgi-bin/tag/list?access_token=' . $this->accessToken['a'] ;
        $urls['media_upload']   = 'https://qyapi.weixin.qq.com/cgi-bin/media/upload?access_token=' . $this->accessToken['a'] . '&type='.$this->type ;
        $urls['media_get']      = 'https://qyapi.weixin.qq.com/cgi-bin/media/get?access_token=' . $this->accessToken['a'] . '&media_id='. $this->mediaId ;
        $urls['menu_create']    = 'https://qyapi.weixin.qq.com/cgi-bin/menu/create?access_token=' . $this->accessToken['a'] . '&agentid=' . $this->agentId ;
        $urls['menu_get']       = 'https://qyapi.weixin.qq.com/cgi-bin/menu/get?access_token=' . $this->accessToken['a'] .'&agentid=' . $this->agentId ;
        $urls['menu_delet']     = 'https://qyapi.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $this->accessToken['a'] .'&agentid=' . $this->agentId ;
        $urls['chat_info']      = 'https://qyapi.weixin.qq.com/cgi-bin/chat/get?access_token=' . $this->accessToken['a'] . '&chatid='. $this->chatId;
        $urls['agent_list'] = 'https://qyapi.weixin.qq.com/cgi-bin/agent/list?access_token=' . $this->accessToken['a'];
        $urls['agent_get']  = 'https://qyapi.weixin.qq.com/cgi-bin/agent/get?access_token=' . $this->accessToken['a'] .'&agentid=' . $this->agentId ;
        $urls['agent_set']  = 'https://qyapi.weixin.qq.com/cgi-bin/agent/set?access_token=' . $this->accessToken['a'] ;
        return $urls ;
    }

}