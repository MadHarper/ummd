<?php

namespace frontend\modules\agreement\models;

/**
 * This is the ActiveQuery class for [[Document]].
 *
 * @see Document
 */
class DocumentQuery extends \yii\db\ActiveQuery
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
            $this->andWhere(['document.iogv_id' => $iogv, 'document.visible' => true]);
        }
    }



    /**
     * @inheritdoc
     * @return Document[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Document|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
