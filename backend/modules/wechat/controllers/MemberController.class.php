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
use yii\helpers\Json;

class MemberController extends BaseController
{

    public function index()
    {

        // 搜索
        $keyword                                   = I('keyword', '', 'string');
        $condition                                 = array('like', '%' . $keyword . '%');
        $map['id|userid|name|email|mobile'] = array(
            $condition,
            $condition,
            $condition,
            $condition,
            $condition,
            '_multi' => true,
        );

        // 获取所有用户
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object   = M('User');
        $list     = $user_object
            ->page($p, 20)
            ->where($map)
            ->order('id desc')
            ->select();
        $listNum = $user_object->where($map)->count() ;
        $show = new Page($listNum, 4);

        $assign_data = array(
            'frs_title'     => '首页',
            'sec_title'     => '微信管理',
            'meta_title'    => '微信成员管理',
            'list_title'    => '微信成员列表',
            'updatetime'    => time()-800,
            'listnum'       => $listNum,
            'list'          => $list,
            'show'          => $show
        );

        $this->assign($assign_data);
        $this->display();
    }

    /**
     *
     * 获取部门成员
     */
    public function indexx()
    {
        $weChat = new Wechat();
        $userListCon = $weChat->getUserList(1);
        if ($userListCon === false ) {
            echo $weChat->getErr();
        } else {
            dump($userListCon);
        }
    }

    /**
     *
     * 获取成员详细信息
     */
    public function getMemberInfo($id)
    {
        //$id = 'zhangsan5' ;
        $weChat = new Wechat() ;
        $memberInfo = $weChat->getUserInfo($id) ;
        if ($memberInfo === false ) {
            //echo $weChat->getErr();
            return false ;
        } else {
            //dump($memberInfo);
            return $memberInfo ;
        }
    }

    /**
     *
     * 获取部门成员详细信息
     */
    public function getMemListInfo()
    {
        $weChat = new Wechat();
        $userListCon = $weChat->getUserList(1,'x');
        if ($userListCon === false ) {
            //echo $weChat->getErr();
            return false ;
        } else {
            //dump($userListCon);
            return $userListCon ;
        }
    }

    public function delMemberMore()
    {
        $data = array();
        $data['useridlist'] = array('qiumu_001', 'qiumu_002');
        $weChat = new Wechat();
        $data = $weChat::json_encode($data) ;

        $setCon = $weChat->delMoreUser($data) ;
        if ($setCon === false ) {
            echo $weChat->getErr();
        } else {
            dump($setCon);
        }

    }

    /**
     * @param $userid
     * @return bool|mixed
     * 删除成员
     */
    public function delUser($userid)
    {
        //$userid = 'qiumu_003';
        $weChat = new Wechat();
        $userDel = $weChat->delUser($userid);
        if ($userDel === false ) {
            //echo $weChat->getErr();
            return false ;
        } else {
            //dump($userDel);
            return $userDel;
        }
    }

    /**
     *
     * 更新成员，手机，邮箱，微信号必须唯一
     */
    public function  updateMember($data)
    {

//        $data = array();
//        $data['userid']     = 'liangfs';
//        $data['name']       = '李四2';
//        $data['department'] = array(1);
//        $data['position']   = '后台工程师';
//        $data['mobile']     = '15913215421';
//        $data['gender']     = 1;
//        $data['email']      = "zhangsan@gzdev.com";
//        $data['weixinid']   = 'lisifordev';
//        $data['enable']     = 1;
//        $mediaid = '';
//        if ($mediaid != '') {
//            $data['avatar_mediaid'] = $mediaid;
//        }
//        $data['extattr']   = array(
//            'attrs' =>array(
//                array(
//                    'name' => '爱好',
//                    'value' => '旅游'
//                ),
//                array(
//                    'name'  => '卡号',
//                    'value' => '1234567234'
//                )
//            )
//        );

        $weChat = new Wechat();
        $setCon = $weChat->upCreUser($data, 'update') ;
        if ($setCon === false ) {
            //echo $weChat->getErr();
            return false ;
        } else {
            return $setCon;
        }
    }

    /**
     *
     * 创建成员，手机，邮箱,微信号必须唯一
     */
    public function createMember()
    {
        $data = array();
        $data['userid'] = 'zhangsan4';
        $data['name']   = '张三';
        $data['department'] = array(1,2);
        $data['position']   = '产品经理';
        $data['mobile']     = '15913215424';
        $data['gender']     = 1;
        $data['email']      = 'zhangsan4@gzdev.com';
        $data['weixinid']   = 'zhangsan4dev4';
        $mediaid            = '';
        if ($mediaid != '') {
            $data['avatar_mediaid'] = $mediaid ;
        }
        $data['extattr']   = array(
            'attrs' =>array(
                array(
                    'name' => '爱好',
                    'value' => '旅游'
                ),
                array(
                    'name'  => '卡号',
                    'value' => '1234567234'
                )
            )
        );

        $weChat = new Wechat();
        $setCon = $weChat->upCreUser($data, 'create') ;
        if ($setCon === false ) {
            echo $weChat->getErr();
        } else {
            dump($setCon);
        }
    }

    /**
     *
     * 更新所有用户
     */
    public function ajaxRefreshUsers()
    {
        $con = $this->getMemListInfo();
        if ($con === false ) {
            return Json::encode(['err' => 0]) ;
        } else {
            $users = $con['userlist'];
            $updateUser = D('User');
            foreach($users as $key => $val ) {
                $hasExt             = isset($val['extattr']);
                $val['extattr']     = ($hasExt) ? ( ($val['extattr'] != '') ?  json_encode($val['extattr']) : '') : '' ;
                $val['department']  = json_encode($val['department']);
                $updateUser->updateUser($val);
            }
            return Json::encode(['err' => 1]) ;
        }
    }

    /**
     *
     * 更新单个用户
     */
    public function ajaxRefreshUser()
    {
        $userid = I('post.userid');
        $con = $this->getMemberInfo($userid);
        if ($con === false ) {
            return Json::encode(['err' => 0]) ;
        }
        $hasExt             = isset($con['extattr']);
        $con['extattr']     = ($hasExt) ? ( ($con['extattr'] != '') ?  json_encode($con['extattr']) : '') : '' ;
        $con['department']  = json_encode($con['department']);
        $updateUser = D('User');
        $map = array('userid'=>$userid);
        $recon = $updateUser->editData($map, $con);
        if ($recon !== false ) {
            return Json::encode(['err' => 1]) ;
        }
        return Json::encode(['err' => 0]) ;
    }

    /**
     *
     * 删除用户
     */
    public function ajaxDelUser()
    {
        $userid = I('post.userid');
        $con = $this->delUser($userid);
        if ($con === false ) {
            return Json::encode(['err' => 0]) ;
        }
        $delUser = D('User');
        $map = array('userid' => $userid);
        $recon = $delUser->deleteData($map);
        if ($recon !== false ) {
            return Json::encode(['err' => 1]) ;
        }
        return Json::encode(['err' => 0]) ;
    }

    /**
     *
     * 解禁用户
     */
    public function ajaxEnableUser()
    {
        $request        = \Yii::$app->getRequest() ;
        $isPost         = $request->getIsPost() ;
        if ($isPost) {
            $userid         = $request->post('userid') ;
            $status         = $request->post('status') ;
            $data['userid'] = $userid ;
            $data['enable'] = $status ;
            $cona = $this->updateMember($data);
            if ($cona === false ) {
                return Json::encode(['err' => 0]) ;
            }
            //解禁完成，再次从微信拉取,入库
            $conb = $this->getMemberInfo($userid);
            if ($conb === false ) {
                return Json::encode(['err' => 0]) ;
            }
            $hasExt             = isset($conb['extattr']);
            $conb['extattr']     = ($hasExt) ? ( ($conb['extattr'] != '') ?  json_encode($conb['extattr']) : '') : '' ;
            $conb['department']  = json_encode($conb['department']);
            $updateUser = D('User');
            $map = array('userid'=>$userid);
            $recon = $updateUser->editData($map, $conb);
            if ($recon !== false ) {
                return Json::encode(['err' => 1]) ;
            }
            return Json::encode(['err' => 0]) ;
        } else {
            return Json::encode(['err' => 0]) ;
        }
    }

}