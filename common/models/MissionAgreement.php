<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mission_agreement".
 *
 * @property int $id
 * @property int $mission_id
 * @property int $agreement_id
 *
 * @property Agreement $agreement
 * @property Mission $mission
 */
class MissionAgreement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mission_agreement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mission_id', 'agreement_id'], 'required'],
            [['mission_id', 'agreement_id'], 'integer'],
            [['mission_id'], 'unique', 'targetAttribute' => ['mission_id', 'agreement_id'], 'message' => 'Такая комбинация командировка - соглашение уже существет.'],
            [['agreement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agreement::className(), 'targetAttribute' => ['agreement_id' => 'id']],
            [['mission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mission::className(), 'targetAttribute' => ['mission_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mission_id' => 'Mission ID',
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
    public function getMission()
    {
        return $this->hasOne(Mission::className(), ['id' => 'mission_id']);
    }
}
