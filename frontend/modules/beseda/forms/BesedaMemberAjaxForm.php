<?php

namespace frontend\modules\beseda\forms;


use common\models\Employee;
use common\models\BesedaEmployee;
use yii\base\Model;
use Yii;

class BesedaMemberAjaxForm extends Model
{

    public $iogv;
    public $employee;
    public $beseda_id;

    public function rules()
    {
        return [
            [['employee', 'beseda_id'], 'required'],
            [['employee', 'iogv', 'beseda_id'], 'integer'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'iogv' => 'Наименование организации',
            'employee' => 'Сотрудник',
        ];
    }





    public function save()
    {
        if($this->validate()){
            $employee = new BesedaEmployee();
            $employee->beseda_id = $this->beseda_id;
            $employee->employee_id = $this->employee;

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