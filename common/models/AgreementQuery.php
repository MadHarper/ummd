<?php

namespace common\models;

use yii\db\ActiveQuery;

class AgreementQuery extends ActiveQuery
{
    public function init()
    {
        parent::init();

        if (!\Yii::$app->user->can('changeAllAgrements')) {

            $iogv = \Yii::$app->user->identity->iogv_id;
            $this->andWhere(['agreement.iogv_id' => $iogv]);
        }
    }
}