<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/9
 * Time: 21:41
 */

namespace backend\modules\wechat\controllers;

use common\controllers\BaseController ;
use common\util\Wechat;


class TagController extends  BaseController
{

    public function index()
    {
        $assign_data = array(
            'meta_title'    => '微信标签列表',
            'sec_title'     => '微信标签管理',
            'frs_title'     => '微信管理',
        );
        $this->assign($assign_data);
        $this->display();
    }

    public function indexx()
    {
        $weChat = new Wechat();
        $tagListCon = $weChat->getTagList();
        if ($tagListCon === false ) {
            echo $weChat->getErr();
        } else {
            dump($tagListCon);
        }
    }

    public function delTagUser()
    {
        $data = array();
        $data['tagid']      = 1;
        $data['userlist']   = array('liangfs', 'liangfsh');
        $data['partylist']  = array(2);

        $weChat = new Wechat();
        $setCon = $weChat->delAddTagUser($data, 'delet_user') ;
        if ($setCon === false ) {
            echo $weChat->getErr();
        } else {
            dump($setCon);
        }

    }

    public function addTagUser()
    {
        $data               = array();
        $data['tagid']      = 1 ;
        $data['userlist']   = array('liangfs', 'liangfsh');
        $data['partylist']  = array('74');

        $weChat = new Wechat();
        $setCon = $weChat->delAddTagUser($data, 'add_user');
        if ($setCon === false ) {
            echo $weChat->getErr() ;
        } else {
            dump($setCon) ;
        }
    }

    public function getTagUser()
    {
        $tagid = 2;
        $weChat = new Wechat();
        $tagCon = $weChat->getTagUser($tagid);
        if ($tagCon === false ) {
            echo $weChat->getErr();
        } else {
            dump($tagCon);
        }
    }

    public function delTag()
    {
        $tagid = 3 ;
        $weChat = new Wechat();
        $delTag = $weChat->delTag($tagid) ;
        if ($delTag === false) {
            echo $weChat->getErr() ;
        } else {
            dump($delTag);
        }
    }

    public function updateTag()
    {
        $data = array();
        $data['tagid']      = 3 ;
        $data['tagname']    = '测试标签xxs' ;

        $weChat = new Wechat();
        $setCon = $weChat->delAddTagUser($data, 'update');
        if ($setCon === false ) {
            echo $weChat->getErr() ;
        } else {
            dump($setCon) ;
        }
    }

    public function createTag()
    {
        $data = array();
        $data['tagname']    = '测试标签xx' ;

        $weChat = new Wechat();
        $setCon = $weChat->delAddTagUser($data, 'create');
        if ($setCon === false ) {
            echo $weChat->getErr() ;
        } else {
            dump($setCon) ;
        }
    }

}