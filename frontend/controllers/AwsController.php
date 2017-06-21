<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 17:50
 */

namespace frontend\controllers;


use yii\web\Controller;
use common\util\Api ;
use common\util\Wechat ;
use common\util\Code ;

class AwsController extends Controller
{
    /**
     * @return bool
     * 获取员工信息
     * json格式的数据包包含两个参数:type(0为查询单个员工，1为批量获取),ygbm(员工编码，如批量获取第一次可以不填)
     */
    public function actionGetAwsUsers()
    {
        $api        = new Api();
        $set        = array(
            'aws_secret'        => 'bjsasc_hr',
            'aws_key'           => 'bjsasc_hr',
            'aws_url'           => 'http://1.202.156.5:9020/openapi',
            'cmd'               => 'hr.getKqglRyxx'
        );
        $data       = '{"type":"0","ygbm":"9914015582"}' ; // 访问单个人员
        //$data       = '{"type":"1","ygbm":""}' ; // 批量访问
        $result     = $api->postAws($data, $set);
        if ($result === false ) {
            $weChat     = new Wechat();
            $msg        = Code::getErr() ;
            $weChat->getLogs($msg) ;
            return false ;
        } else {
            var_dump($result) ;
            return $result ;
        }
    }

    /**
     * 按月获取员工的考勤状态
     */
    public function actionGetUserByMon()
    {
        $api        = new Api();
        $set        = array(
            'aws_secret'        => 'bjsasc_hr',
            'aws_key'           => 'bjsasc_hr',
            'aws_url'           => 'http://1.202.156.5:9020/openapi',
            'cmd'               => 'hr.getYgztByMonth'
        );
        $data['date']       = '2017-05' ;
        $data['ygbm']       = 'qiumu' ;
        $data               = json_encode($data) ;
        $result             = $api->postAws($data, $set);
        if ($result === false ) {
            $weChat     = new Wechat();
            $msg        = Code::getErr() ;
            $weChat->getLogs($msg) ;
            return false ;
        } else {
            var_dump($result) ;
            return $result ;
        }
    }

    /**
     * @return bool
     * 提交考勤数据到AWS
     */
    public function actionPushClockDataToAws()
    {
        $api        = new Api();
        $set        = array(
            'aws_secret'        => 'bjsasc_hr',
            'aws_key'           => 'bjsasc_hr',
            'aws_url'           => 'http://1.202.156.5:9020/openapi',
            'cmd'               => 'hr.kqrb3'
        );
        $time       = time();
        //生成json数据
        $data['data']['list'][0]        = array(
            'staff_id'      => 'qiumu',
            'check_time'    => $time
        );
        $data['data']['list'][1]        = array(
            'staff_id'      => 'qiumu',
            'check_time'    => $time
        );
        $data['data']['list'][2]        = array(
            'staff_id'      => 'qiumu',
            'check_time'    => $time
        );
        $data               = json_encode($data) ;
        $result             = $api->postAws($data, $set);
        if ($result === false ) {
            $weChat     = new Wechat();
            $msg        = Code::getErr() ;
            $weChat->getLogs($msg) ;
            return false ;
        } else {
            var_dump($result) ;
            return $result ;
        }
    }

    /**
     * 获取考勤GPS数据
     * hr.getKqglYggz 员工规则
     * hr.getKqglBgdz 考勤地址
     * hr.getKqglBc 班次规则
     */
    public function actionGetAwsGps()
    {
        $api        = new Api();
        $set        = array(
            'aws_secret'        => 'bjsasc_hr',
            'aws_key'           => 'bjsasc_hr',
            'aws_url'           => 'http://1.202.156.5:9020/openapi',
            'cmd'               => 'hr.getKqglYggz'
        );
        $result             = $api->getAws($set);
        var_dump($result) ;
    }


}