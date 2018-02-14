<?php

namespace frontend\services;

use Yii;
use common\models\Organization;
use common\models\Employee;


class EmployeeOptionsGenerator
{

    public function generateOptions($id, $historic)
    {
        $iogv = Organization::findOne($id);
        if($iogv->history) {
            $currentIogv = Organization::find()
                ->where(['history' => false, 'main_id' => $iogv->main_id])
                ->one();

            $id = $currentIogv->id;
        }

        if($historic == 1){
            $employees = Employee::find()
                ->where(['organization_id' => $id])
                ->orderBy('fio')
                ->all();
        }else{
            $employees = Employee::find()
                ->where(['organization_id' => $id, 'history' => false])
                ->orderBy('fio')
                ->all();
        }

        $list = "";
        foreach ($employees as $emp){
            $style = $emp->history ? 'class="historic_drop"' : '';
            $list .= '<option value="' . $emp->id . '" ' . $style . '>' . $emp->fio . " - " . $emp->position .'</option>';
        }

        return $list;
    }

}
