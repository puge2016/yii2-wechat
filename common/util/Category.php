<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/4
 * Time: 19:21
 */

namespace common\util ;

use yii\db\ActiveRecord ;


class Category extends ActiveRecord
{
    public static $tree = [] ;

    /**
     * @inheritdoc
     *
     */

    public function getCatTree($cats , $bclassid = 0, $nu = 0 )
    {
        $bx = '---|' ;
        $nu++ ;
        foreach ($cats as $cat){
            $catid		= $cat['classid'] === null ? 0 : $cat['classid'] ;
            $catname	= $cat['classname'] === null ? 'null' : $cat['classname'] ;
            $catbid		= $cat['bclassid'] === null ? 0 : $cat['bclassid'] ;
            $islast     = $cat['islast'] === null ? 0 : $cat['islast'] ;
            if ($catbid == $bclassid) {
                self::$tree[$catid]= str_repeat($bx, $nu) .'~'. $catname .'['.$catid.']'. ($islast ? '_last' : '') . PHP_EOL  ;
                $this->getCatTree($cats, $catid, $nu ) ;
            }
        }
    }

    public function getTree($cats)
    {
        $this->getCatTree($cats, 0 , 0);
        return self::$tree ;
    }
}