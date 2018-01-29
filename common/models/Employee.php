<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "employee".
 *
 * @property int $id
 * @property string $fio
 * @property string $position
 * @property bool $active
 * @property int $organization_id
 * @property int $created_at
 * @property int $updated_at
 * @property bool $history
 * @property bool $visible
 * @property int $prev_id
 * @property int $main_id
 *
 * @property Organization $organization
 * @property SideAgr[] $sideAgrs
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return date('U'); },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active', 'visible', 'history'], 'boolean'],
            [['organization_id'], 'default', 'value' => null],
            [['history'], 'default', 'value' => false],
            [['visible'], 'default', 'value' => true],
            [['prev_id'], 'default', 'value' => null],
            [['organization_id', 'created_at', 'updated_at', 'prev_id', 'main_id'], 'integer'],
            [['fio', 'position'], 'string', 'max' => 255],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'Ф.И.О.',
            'position' => 'Должность',
            'active' => 'Активен',
            'organization_id' => 'Организация',
            'visible' => 'Видимый',
            'created_at' => 'Создан',
            'updated_at' => 'Отредактирован'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSideAgrs()
    {
        return $this->hasMany(SideAgr::className(), ['employee_id' => 'id']);
    }
}
