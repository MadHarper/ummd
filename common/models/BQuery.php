<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Beseda]].
 *
 * @see Beseda
 */
class BQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/



    public function init()
    {
        parent::init();

        if (!\Yii::$app->user->can('changeAllAgrements')) {

            $iogv = \Yii::$app->user->identity->iogv_id;
            $this->andWhere(['beseda.iogv_id' => $iogv]);
        }
    }


    /**
     * {@inheritdoc}
     * @return Beseda[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Beseda|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
