<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%clock}}".
 *
 * @property integer $id
 * @property string $userid
 * @property string $latitude
 * @property string $longitude
 * @property string $point_title
 * @property string $point_content
 * @property integer $wetype
 * @property string $wedate
 * @property integer $created_at
 */
class Clock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%clock}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'point_title', 'point_content', 'wetype', 'created_at'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['wetype', 'created_at'], 'integer'],
            [['wedate'], 'safe'],
            [['userid'], 'string', 'max' => 80],
            [['point_title', 'point_content'], 'string', 'max' => 255],
            [['userid', 'created_at'], 'unique', 'targetAttribute' => ['userid', 'created_at'], 'message' => 'The combination of 成员微信ID and 创建时间 has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '自增ID'),
            'userid' => Yii::t('app', '成员微信ID'),
            'latitude' => Yii::t('app', '纬度'),
            'longitude' => Yii::t('app', '经度'),
            'point_title' => Yii::t('app', '地址'),
            'point_content' => Yii::t('app', '详细地址'),
            'wetype' => Yii::t('app', '考勤类别'),
            'wedate' => Yii::t('app', '考勤日期'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }

    public function updateClock($datas)
    {
        $map        = [
            'userid'        => $datas['userid'] ,
            'created_at'    => $datas['created_at']
        ] ;
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
     * @inheritdoc
     * @return ClockQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClockQuery(get_called_class());
    }
}
