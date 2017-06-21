<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/9
 * Time: 20:47
 */

namespace backend\modules\wechat\controllers;

use yii\helpers\Json ;
use common\controllers\BaseController;
use common\util\Code;
use common\util\Wechat;
use common\models\Dep ;

class DepartmentController extends BaseController
{
    /**
     * 部门管理首页
     */
    public function actionIndex()
    {
        $htmlDatas          = [
          'meta_title'              => '微信部门列表',
          'sec_title'               => '微信部门管理',
          'frs_title'               => '微信管理',
        ];
        $this->renderPartial('index',[
            'htmlDatas'             => $htmlDatas
        ]);
    }

    /**
     * 获取部门信息
     * @param int $id
     * @return bool|mixed
     */
    public function getDepartments($id=0)
    {
        $weChat = new Wechat();
        $depListCon = $weChat->getDepartment($id);
        if ($depListCon === false ) {
            \Yii::info(Code::getErr(), 'wechat') ;
            return false ;
        } else {
            return $depListCon ;
        }
    }

    /**
     * 删除部门
     * @param $id
     * @return bool|mixed
     */
    public function delDepartment($id)
    {
        //$id = 73;
        $weChat = new Wechat();
        $delMemCon = $weChat->delDepartment($id);
        if ($delMemCon === false ) {
            \Yii::info(Code::getErr(), 'wechat') ;
            return false ;
        } else {
            return $delMemCon ;
        }
    }

    /**
     * 更新部门信息
     * @param $data
     * @return bool|mixed|string
     */
    public function updateDepartment($data)
    {
//        $data = array();
//        $data['id'] = 74;
//        $data['name'] = '测试部门3';
//        $data['parentid'] = 1 ;
//        $data['order']    = 1 ;

        $weChat = new Wechat();
        $setCon = $weChat->upCreDepartment($data, 'update') ;
        if ($setCon === false ) {
            \Yii::info(Code::getErr(), 'wechat') ;
            return false ;
        } else {
            return $setCon ;
        }
    }

    /**
     * 创建部门
     * @param $data
     * @return bool|mixed|string
     */
    public function createDepartment($data)
    {
//        $data = array();
//        $data['name'] = '测试部门';
//        $data['parentid']   = 1 ;
//        $data['order']      = 1 ;
//        $data['id']         = 1 ;

        $weChat = new Wechat();
        $setCon = $weChat->upCreDepartment($data, 'create') ;
        if ($setCon === false ) {
            \Yii::info(Code::getErr(), 'wechat') ;
            return false ;
        } else {
            return $setCon ;
        }
    }

    /**
     * 拉取微信企业号部门,以微信企业号后台为准
     */
    public function ajaxRefreshDeps()
    {
        $request        = \Yii::$app->getRequest() ;
        $isPost         = $request->getIsPost() ;
        if ($isPost) {
            $id         = $request->post('id') ;
            $con        = $this->getDepartments($id) ;
            if ($con === false ) {
                return Json::encode(['err'=>0]) ;
            }
            $department     = $con['department'];
            $datas          = array();
            $cid            = 1 ;
            $countNum = count($department) ;
            for ($i=0;$i<$countNum;$i++) {
                $datas[$i]['did']       = $department[$i]['id'] ;
                $datas[$i]['parentid']  = $department[$i]['parentid'];
                $datas[$i]['dname']     = $department[$i]['name'];
                $datas[$i]['dsort']     = $department[$i]['order'];
                $datas[$i]['cid']       = $cid;
            }


            $depSql         = new Dep() ;
            $field          = ['did','cid','parentid','dname'] ;
            $updateDepCon   = $depSql->find()->asArray()->select($field)->all();
            $isEmpty        = empty($updateDepCon);
            if ($isEmpty) {
                //如果为空则批量插入数据
                $depSql->addAll($datas);
            } else {
                $sqlDids    = array_column($updateDepCon, 'did');
                $chatDids   = array_column($datas, 'did');
                $diffSql    = $this->array_diff($sqlDids, $chatDids);
                $diffChat   = $this->array_diff($chatDids, $sqlDids);
                $countSql   = count($diffSql[0]);
                $countChat  = count($diffChat[0]);

                if ($countSql ==0 && $countChat==0) {
                    foreach ($sqlDids as $key => $val) {
                        $sqlDid     = $val ;
                        $chatKey    = array_search($sqlDid, $chatDids);
                        // 如果部门名称不相同 或者上级ID不一样更新，以企业号后台为准
                        if ($updateDepCon[$key]['dname'] != $datas[$chatKey]['dname'] || $updateDepCon[$key]['parentid'] != $datas[$chatKey]['parentid']  ) {
                            $updateDname['did'] = $val ;
                            $updateDname['cid'] = $cid ;
                            $updateCon          = $datas[$chatKey] ;
                            $updateDep          = Dep::findOne($updateDname) ;
                            $updateDep->attributes = $updateCon ;
                            $updateDep->save();
                        }
                    }
                }

                if ($countSql > 0) {
                    // 如果本地数据有企业号没有的数据，删除这部分本地数据
                    $dids   = implode(',',$diffSql[0]);
                    $where  = 'did in('.$dids.') and cid='.$cid ;
                    $depSql::deleteAll($where) ;

                    // 操作本地与企业号相同的数据
                    foreach ($diffSql[1] as $key => $val ) {
                        $sqlDid     = $val ;
                        $chatKey    = array_search($sqlDid, $chatDids);
                        // 如果部门名称不相同 或者上级ID不一样更新，以企业号后台为准
                        if ($updateDepCon[$key]['dname'] != $datas[$chatKey]['dname'] || $updateDepCon[$key]['parentid'] != $datas[$chatKey]['parentid']  ) {
                            $updateDname['did'] = $val ;
                            $updateDname['cid'] = $cid ;
                            $updateCon          = $datas[$chatKey] ;
                            $updateDep          = $depSql::findOne($updateDname) ;
                            $updateDep->attributes = $updateCon ;
                            $updateDep->save() ;
                        }
                    }
                }
                if ($countChat > 0 ) {
                    $diffDatas      = array() ;
                    //如果企业号数据有本地没有的数据，增加这部分数据到本地
                    foreach ($diffChat[0] as $key => $val) {
                        $diffDatas[$key]['did']       = $datas[$key]['did'] ;
                        $diffDatas[$key]['parentid']  = $datas[$key]['parentid'];
                        $diffDatas[$key]['dname']     = $datas[$key]['dname'];
                        $diffDatas[$key]['dsort']     = $datas[$key]['dsort'];
                        $diffDatas[$key]['cid']       = $cid;
                    }
                    //TP需要重置KEY，否则无法批量插入
                    $values         = array_values($diffDatas);
                    $depSql->addAll($values) ;

                    //操作企业号与本地相同的数据
                    foreach ($diffChat[1] as $key => $val ) {
                        $chatDid     = $val ;
                        $chatKey    = array_search($chatDid, $sqlDids);
                        // 如果部门名称不相同 或者上级ID不一样更新，以企业号后台为准
                        if ($updateDepCon[$chatKey]['dname'] != $datas[$key]['dname'] || $updateDepCon[$chatKey]['parentid'] != $datas[$key]['parentid']  ) {
                            $updateDname['did'] = $val ;
                            $updateDname['cid'] = $cid ;
                            $updateCon          = $datas[$chatKey] ;
                            $updateDep          = $depSql::findOne($updateDname) ;
                            $updateDep->attributes = $updateCon ;
                            $updateDep->save() ;
                        }
                    }
                }
            }
            return Json::encode(['err' => 1]) ;
        } else {
            return false ;
        }
    }

    public function ajaxJsTreeDeps()
    {
        $getDeps    = new Dep() ;
        $depCon     = $getDeps->find()->asArray()->select(['id','did','parentid','dname'])->all();
        $result     = $this->treeData($depCon);
        echo json_encode($result) ;
    }

    /**
     *
     * operation=1:delete_node , 2:create_node, 3:rename_node, 4:move_node, 5:copy_node
     */
    public function ajaxJstreePost()
    {
        $request        = \Yii::$app->getRequest() ;
        $isPost         = $request->getIsPost() ;
        if ($isPost) {
            $operation      = (int)$request->post('operation', 0) ;
            $doDepartment   = new Dep() ;
            $cid            = 1 ;
            switch ($operation) {
                case 1 ://delet
                    $id     = $request->post('id');
                    $delCon = $this->delDepartment($id) ; // 删除微信企业号里的部门
                    if ($delCon === false ) {
                        return Json::encode(['err' => 0]) ;
                    } else {
                        $map            = array(
                            'did'       => $id,
                            'cid'       => $cid
                        );
                        $delete_node    = $doDepartment::findOne($map)->delete(); // 删除本地数据
                        if ($delete_node !== false ) {
                            return Json::encode(['err' => 1]) ;
                        }
                        return Json::encode(['err' => 0]) ;
                    }
                    break ;
                case 2 ://create
                    $wmap           = array(
                        'name'      => $request->post('text') ,
                        'parentid'  => $request->post('parentid') ,
                    );
                    $createCon      = $this->createDepartment($wmap);
                    if ($createCon === false ) {
                        return Json::encode(['err' => 0]) ;
                    } else {
                        $this->ajaxRefreshDeps();//直接拉取更新
                    }
                    break ;
                case 3 ://rename
                    $wmap        = array(
                        'name'     => $request->post('text') ,
                        'id'       => $request->post('id')
                    );
                    $renameCon  = $this->updateDepartment($wmap);
                    if ($renameCon === false ) {
                        return Json::encode(['err' => 0]) ;
                    } else {
                        $map['cid']     = $cid ;
                        $map['did']     = $request->post('id') ;
                        $con['dname']   = $request->post('text') ;
                        $updateDep                  = $doDepartment::findOne($map) ;
                        $updateDep->attributes      = $con ;
                        $rename_node                = $updateDep->save() ;
                        if ($rename_node !== false ) {
                            return Json::encode(['err' => 1]) ;
                        }
                        return Json::encode(['err' => 0]) ;
                    }
                    break ;
                case 4 ://move
                    $wmap       = array(
                        'id'        => $request->post('id'),
                        'parentid'  => $request->post('parentid')
                    );
                    $moveCon    = $this->updateDepartment($wmap) ;
                    if ($moveCon === false ) {
                        return Json::encode(['err' => 0]) ;
                    } else {
                        $map['did']         = $request->post('id') ;
                        $con['parentid']    = $request->post('parentid') ;
                        $map['cid']         = $cid ;

                        $updateDep                  = $doDepartment::findOne($map) ;
                        $updateDep->attributes      = $con ;
                        $move_node                  = $updateDep->save() ;
                        if ($move_node !== false ) {
                            return Json::encode(['err' => 1]) ;
                        } else {
                            return Json::encode(['err' => 0]) ;
                        }
                    }
                    break ;
                case 5 ://copy_node
                    break;

            }
        }
        return false ;
    }


    /**
     * @param $data
     * @param int $pid
     * @return array
     * 一维数组转为树形数组
     */
    protected function treeData($data,$pid = 0){
        $result = array();
        foreach($data as $v){
            $v['text']  = $v['dname'] ;
            $v['id']    = $v['did'] ;
            if($v['parentid'] == $pid){
                $v['children'] = $this->treeData($data,$v['did']);
                $result[] = $v;
            }
        }
        return $result;
    }

    /**
     * @param $array_1
     * @param $array_2
     * @return array
     * 两个数组对比
     */
    protected function array_diff($array_1, $array_2) {
        $array_2    = array_flip($array_2); //key did 互换
        $array_1_0  = array();
        foreach ($array_1 as $key => $item) {
            if (isset($array_2[$item])) {
                $array_1_0[$key]  = $item ;//相同单元
                unset($array_1[$key]);//减掉array_1单元
            }
        }
        return array($array_1, $array_1_0);// $array_1:减掉相同单元后剩下的， $array_1_0:相同单元
    }

}