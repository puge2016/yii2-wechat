<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord ;

/**
 * This is the model class for table "{{%clock_chat}}".
 *
 * @property integer $id
 * @property integer $staff_id
 * @property integer $checkin_time
 * @property integer $checkout_time
 * @property string $wedate
 */
class ClockChat extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%clock_chat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_id', 'checkin_time', 'checkout_time'], 'integer'],
            [['wedate'], 'safe'],
            [['staff_id', 'wedate'], 'unique', 'targetAttribute' => ['staff_id', 'wedate'], 'message' => 'The combination of 会员ID and 日期 has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '自增ID'),
            'staff_id' => Yii::t('app', '会员ID'),
            'checkin_time' => Yii::t('app', '签到时间'),
            'checkout_time' => Yii::t('app', '签退时间'),
            'wedate' => Yii::t('app', '日期'),
        ];
    }

    public function updateClockChat($datas)
    {
        $map        = [
            'staff_id'      => $datas['staff_id'],
            'wedate'        => $datas['wedate']
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
     * @return ClockChatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClockChatQuery(get_called_class());
    }
}
