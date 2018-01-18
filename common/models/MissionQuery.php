<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Mission]].
 *
 * @see Mission
 */
class MissionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Mission[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Mission|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
