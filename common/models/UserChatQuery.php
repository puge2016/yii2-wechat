<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[UserChat]].
 *
 * @see UserChat
 */
class UserChatQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserChat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserChat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
