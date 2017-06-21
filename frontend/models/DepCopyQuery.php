<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[DepCopy]].
 *
 * @see DepCopy
 */
class DepCopyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return DepCopy[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DepCopy|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
