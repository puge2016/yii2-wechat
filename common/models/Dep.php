<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%dep}}".
 *
 * @property integer $id
 * @property integer $cid
 * @property integer $did
 * @property integer $parentid
 * @property string $dname
 * @property integer $dsort
 * @property integer $updated_at
 * @property integer $created_at
 */
class Dep extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dep}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid'], 'required'],
            [['cid', 'did', 'parentid', 'dsort', 'updated_at', 'created_at'], 'integer'],
            [['dname'], 'string', 'max' => 200],
            [['cid', 'did'], 'unique', 'targetAttribute' => ['cid', 'did'], 'message' => 'The combination of 所属企业号ID and 部门ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '自增ID'),
            'cid' => Yii::t('app', '所属企业号ID'),
            'did' => Yii::t('app', '部门ID'),
            'parentid' => Yii::t('app', '上级部门ID'),
            'dname' => Yii::t('app', '部门名称'),
            'dsort' => Yii::t('app', '部门排序'),
            'updated_at' => Yii::t('app', '更新时间'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }

    //updateDep $insertData

    /**
     * @param $datas
     * @return bool
     * 如果有数据则更新，如果没有数据则增加
     */
    public function updateDep($datas)
    {
        $map        = ['did' => $datas['did'] ] ;
        $result     = self::findOne($map) ;
        $userChat   = $this ;
        if ($result) {
            // 更新数据
            $userChat           = $result ;
        }
        $userChat->attributes       = $datas ;
        if ($userChat->save()) {
            return true ;
        } else {
            Yii::info($this->getErrors(), 'wechat') ;
            return false ;
        }
    }




    /**
     * 批量添加数据
     * @param $datas
     */
    public function addAll($datas)
    {
        $isEmpty            = empty($datas) ;
        $isArray            = is_array($datas) ;
        if (!$isEmpty && $isArray) {
            $filed          = array_keys($datas[0]) ;
            Yii::$app->getDb()->createCommand()->batchInsert(self::tableName(), $filed, $datas)->execute();
            return true ;
        } else {
            return false ;
        }
    }

    /**
     * @inheritdoc
     * @return DepQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DepQuery(get_called_class());
    }
}
