<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\Country;
use common\models\Employee;
use common\models\Organization;
use common\models\Region;


class BesedaBase extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'beseda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => 1],
            [['date_start', 'theme', 'iogv_id', 'date_start', 'address'], 'required'],
            [['theme', 'target', 'notes', 'iogv_id', 'address', 'questions'], 'string'],
            [['created_at', 'updated_at', 'iniciator_id', 'iogv_id'], 'default', 'value' => null],
            [['status', 'created_at', 'updated_at', 'iniciator_id'], 'integer'],
            [['report_overdue'], 'boolean'],
            [['date_start', 'date_start_time', 'report_date', 'control_date', 'event_time', 'agreementsArray'], 'safe'],
            [['iniciator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['iniciator_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'theme' => 'Тема беседы',
            'target' => 'Цель беседы ',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'date_start' => 'Дата начала',
            'date_start_time' => 'Date Start Time',
            'iniciator_id' => 'Инициатор беседы ',
            'report_date' => 'Дата предоставления отчета',
            'control_date' => 'Контрольный срок',
            'status' => 'Статус',
            'notes' => 'Служебные пометки',
            'iogv_id' => 'Iogv ID',
            'event_time' => "Время встречи",
            'address' => "Адрес",
            'questions' => "Вопросы, обсуждаемые на беседе",
            'report_overdue' => "Нарушен срок предоставления отчета"
        ];
    }

}
