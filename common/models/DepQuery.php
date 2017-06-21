<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Dep]].
 *
 * @see Dep
 */
class DepQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Dep[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Dep|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
