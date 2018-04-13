<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "beseda_agreement".
 *
 * @property int $id
 * @property int $beseda_id
 * @property int $agreement_id
 *
 * @property Agreement $agreement
 * @property Beseda $beseda
 */
class BesedaAgreement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'beseda_agreement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['beseda_id', 'agreement_id'], 'default', 'value' => null],
            [['beseda_id', 'agreement_id'], 'integer'],
            [['agreement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agreement::className(), 'targetAttribute' => ['agreement_id' => 'id']],
            [['beseda_id'], 'exist', 'skipOnError' => true, 'targetClass' => Beseda::className(), 'targetAttribute' => ['beseda_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'beseda_id' => 'Beseda ID',
            'agreement_id' => 'Agreement ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgreement()
    {
        return $this->hasOne(Agreement::className(), ['id' => 'agreement_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeseda()
    {
        return $this->hasOne(Beseda::className(), ['id' => 'beseda_id']);
    }

    /**
     * {@inheritdoc}
     * @return BesedaAgreementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BesedaAgreementQuery(get_called_class());
    }
}
