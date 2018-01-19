<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property int $id
 * @property string $fio
 * @property string $position
 * @property bool $active
 * @property int $organization_id
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active', 'visible'], 'boolean'],
            [['organization_id'], 'default', 'value' => null],
            [['organization_id'], 'integer'],
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
            'visible' => 'Видимый'
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
