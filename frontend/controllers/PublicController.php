<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16
 * Time: 17:44
 */

namespace frontend\controllers;

use Yii ;
use yii\web\Controller ;

class PublicController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class'             => 'common\util\ErrorAction',
                'isPartial'         => 1 ,
                'userErrDef'    => Yii::$app->params['userErrDef'] ,
            ],
        ];
    }

    public function actionJump()
    {
        return $this->renderPartial('jump',[
            'name'          => '跳转页面',
            'jumpUrl'       => 'site/index',
            'wemsg'         => 'wemsg',
            'waitSecond'    => 9,
            'success'       => 0
        ]);
    }

}