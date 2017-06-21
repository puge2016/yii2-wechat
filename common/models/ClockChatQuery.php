<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[ClockChat]].
 *
 * @see ClockChat
 */
class ClockChatQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ClockChat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ClockChat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
