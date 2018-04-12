<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[BesedaAgreement]].
 *
 * @see BesedaAgreement
 */
class BesedaAgreementQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return BesedaAgreement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return BesedaAgreement|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
