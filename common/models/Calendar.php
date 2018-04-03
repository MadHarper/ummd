<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "calendar".
 *
 * @property int $id
 * @property string $day_date
 * @property bool $is_holliday
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calendar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['day_date'], 'safe'],
            [['is_working'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'day_date'      => 'День',
            'is_working'    => 'Рабочий день',
        ];
    }
}
