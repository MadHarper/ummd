<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mission_result".
 *
 * @property int $id
 * @property string $result
 * @property int $mission_id
 *
 * @property Mission $mission
 */
class MissionResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mission_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['result'], 'string'],
            [['mission_id'], 'default', 'value' => null],
            [['mission_id'], 'integer'],
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
            'result' => 'Результат командировки',
            'mission_id' => 'Mission ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMission()
    {
        return $this->hasOne(Mission::className(), ['id' => 'mission_id']);
    }
}
