<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Blocky]].
 *
 * @see Blocky
 */
class BlockyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Blocky[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Blocky|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
