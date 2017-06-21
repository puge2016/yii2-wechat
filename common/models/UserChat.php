<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord ;

/**
 * This is the model class for table "{{%user_chat}}".
 *
 * @property integer $id
 * @property integer $staff_id
 * @property string $avatar
 * @property string $department
 * @property integer $gender
 * @property string $mobile
 * @property string $name
 * @property string $position
 * @property integer $status
 * @property string $userid
 * @property string $weixinid
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserChat extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_chat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_id', 'avatar', 'department', 'gender', 'mobile', 'name', 'status', 'userid'], 'required'],
            [['staff_id', 'gender', 'status', 'created_at', 'updated_at'], 'integer'],
            [['avatar', 'department'], 'string', 'max' => 255],
            [['mobile', 'weixinid'], 'string', 'max' => 30],
            [['name', 'position', 'userid'], 'string', 'max' => 80],
            [['staff_id'], 'unique'],
            [['userid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '自增ID'),
            'staff_id' => Yii::t('app', '员工编号'),
            'avatar' => Yii::t('app', '微信头像'),
            'department' => Yii::t('app', '部门'),
            'gender' => Yii::t('app', '性别'),
            'mobile' => Yii::t('app', '手机号'),
            'name' => Yii::t('app', '成员名字'),
            'position' => Yii::t('app', '职位'),
            'status' => Yii::t('app', '状态'),
            'userid' => Yii::t('app', '成员ID'),
            'weixinid' => Yii::t('app', '微信ID'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ]
        ];
    }

    /**
     * @inheritdoc
     * @return UserChatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserChatQuery(get_called_class());
    }

    /**
     * @param $userDatas
     * @return bool
     * 如果有数据则更新，如果没有数据则增加
     */
    public function updateUser($userDatas)
    {
        $map        = ['staff_id' => $userDatas['staff_id'] ] ;
        $result     = self::findOne($map) ;
        $userChat   = $this ;
        if ($result) {
            // 更新数据
            $userChat           = $result ;
        }
        $userChat->attributes       = $userDatas ;
        if ($userChat->save()) {
            return true ;
        } else {
            Yii::info($this->getErrors(), 'wechat') ;
            return false ;
        }
    }
}
