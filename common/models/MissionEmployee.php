<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mission_employee".
 *
 * @property int $id
 * @property int $mission_id
 * @property int $employee_id
 * @property int $role
 *
 * @property Employee $employee
 * @property Mission $mission
 */
class MissionEmployee extends \yii\db\ActiveRecord
{


    const ROLE_MEMBER   = 1;
    const ROLE_BOSS     = 2;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mission_employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mission_id', 'employee_id', 'role'], 'required'],
            [['mission_id', 'employee_id', 'role'], 'integer'],
            ['boss', 'boolean'],
            [['boss'], 'default', 'value' => false],
            [['employee_id'], 'unique', 'targetAttribute' => ['employee_id', 'mission_id'], 'message' => 'Повторное добавление сотрудника.'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
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
            'mission_id' => 'Командировка',
            'employee_id' => 'Сотрудник',
            'role' => 'Роль',
            'boss' => 'Глава ИОГВ'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMission()
    {
        return $this->hasOne(Mission::className(), ['id' => 'mission_id']);
    }

    public static function getMissionRolesList(){
        return [
            self::ROLE_MEMBER   => "Участник",
            self::ROLE_BOSS     => "Глава"
        ];
    }

    public function getMemberMissionRole(){
        if(isset($this->role)){
            $list = self::getMissionRolesList();
            return $list[$this->role];
        }
        return false;
    }
}
