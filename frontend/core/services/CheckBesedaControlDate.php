<?php

namespace frontend\core\services;


use common\models\Calendar;
use common\models\Beseda;
use Yii;


class CheckBesedaControlDate
{


    const RED_STYLE     = ['style' => 'background-color:#f15f40; color:aliceblue'];
    const YELLOW_STYLE  = ['style' => 'background-color:#e2db3e;'];




    public function checkForGrid($model)
    {
        $today = date("Y-m-d");

        $id = $model->id;

        if($model->report_date){
            return false;
        }


        $a = $model->control_date;
        $b= 4;

        if(isset($model->control_date)){

            if($today >= $model->control_date){
                return self::RED_STYLE;
            }

            $cnt = Calendar::find()
                //->where(['between', 'day_date', $yesterday, $model->contol_date])
                ->where(['>', 'day_date', $today])
                ->andWhere(['<=', 'day_date', $model->control_date])
                ->andWhere(['is_working' => true])
                ->count();

            if($cnt > 1 && $cnt <= 3){
                return self::YELLOW_STYLE;
            }

            if($cnt === 1){
                return self::RED_STYLE;
            }

        }
        return false;
    }
}
