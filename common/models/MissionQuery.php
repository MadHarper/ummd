<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Mission]].
 *
 * @see Mission
 */
class MissionQuery extends \yii\db\ActiveQuery
{
    public function init()
    {
        parent::init();

        if (!\Yii::$app->user->can('changeAllAgrements')) {

            $iogv = \Yii::$app->user->identity->iogv_id;
            $this->andWhere(['mission.iogv_id' => $iogv]);
        }
    }

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
