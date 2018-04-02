<?php

namespace frontend\modules\mission\forms;


use common\models\Employee;
use common\models\MissionEmployee;
use yii\base\Model;
use Yii;

class MissionMemberAjaxForm extends Model
{

    public $iogv;
    public $employee;
    public $role;
    public $mission_id;
    public $boss;

    public function rules()
    {
        return [
            [['employee', 'role', 'mission_id'], 'required'],
            [['employee', 'role', 'iogv', 'mission_id'], 'integer'],
            ['boss', 'boolean'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'iogv' => 'ИОГВ',
            'employee' => 'Сотрудник',
            'role' => 'Роль',
            'boss' => 'Глава ИОГВ'
        ];
    }





    public function save()
    {
        if($this->validate()){
            $employee = new MissionEmployee();
            $employee->mission_id = $this->mission_id;
            $employee->employee_id = $this->employee;
            $employee->role = $this->role;
            $employee->boss = $this->boss;

            if($employee->save()){
                return null;
            }
            $arr = array_values($employee->getFirstErrors());

        }else{
            $arr = array_values($this->getFirstErrors());
        }

        return $arr[0];
    }
}