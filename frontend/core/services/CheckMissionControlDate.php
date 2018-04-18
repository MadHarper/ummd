<?php

namespace frontend\core\services;


use common\models\Calendar;
use common\models\Mission;
use common\models\MissionEmployee;
use Yii;


class CheckMissionControlDate
{

    private $missionId;
    private $overallDaysNumber = 5;

    const RED_STYLE     = ['style' => 'background-color:#f15f40; color:aliceblue'];
    const YELLOW_STYLE  = ['style' => 'background-color:#e2db3e;'];



    public function __construct($missionId)
    {
        $this->missionId = $missionId;
    }

    // Применяется когда у командировки или изменились дата конца.
    // Или когда поменялся состав участников (удалился или добавился)
    public function check()
    {
        // 1. проверяем всех участников командировки на наличие главы
        // 2. Вычисляем дату контрольного срока control_date
        // 3. Меняем ее или не меняем

        $mission = Mission::find()->where(['id' => $this->missionId])->one();
        if(!$mission){
            Yii::error("Ошибка в методе frontend\core\servicesCheckMissionControlDate::check. Не найдена сущность командировки");
            return;
        }

        $boss = MissionEmployee::find()->where(['mission_id' => $this->missionId, 'boss' => true])->all();
        if($boss){
            $this->overallDaysNumber = 10;
        }

        $days = Calendar::find()
                        ->where(['>', 'day_date', $mission->date_end])
                        ->andWhere(['is_working' => true])
                        ->orderBy('day_date')
                        ->limit($this->overallDaysNumber)
                        ->all();


        if($boss){
            $mission->with_boss = true;
        }else{
            $mission->with_boss = false;
        }


        // В случае если нет дней в календаре, например следующий год и нет данных
        if(!$days || count($days) < $this->overallDaysNumber){
            $mission->contol_date = null;
            $mission->save(false);
            return;
        }

        $controlCalendarDay = array_pop($days);
        if($mission->contol_date != $controlCalendarDay->day_date){
            $mission->contol_date = $controlCalendarDay->day_date;
        }

        $today = date("Y-m-d");
        if(!$mission->report_date && $today > $mission->contol_date){
            $mission->report_overdue = true;
        }

        $mission->save(false);
    }


    public function checkForGrid($model)
    {
        $today = date("Y-m-d");

        if(isset($model->report_date)){
            return false;
        }

        if(isset($model->contol_date)){
            if($today >= $model->contol_date){
                return self::RED_STYLE;
            }

            $cnt = Calendar::find()
                        //->where(['between', 'day_date', $yesterday, $model->contol_date])
                        ->where(['>', 'day_date', $today])
                        ->andWhere(['<=', 'day_date', $model->contol_date])
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


    public function checkAjax($date_end){
        //$mission = Mission::find()->where(['id' => $this->missionId])->one();

        $boss = MissionEmployee::find()->where(['mission_id' => $this->missionId, 'boss' => true])->all();
        if($boss){
            $this->overallDaysNumber = 10;
        }


        $days = Calendar::find()
            ->where(['>', 'day_date', $date_end])
            ->andWhere(['is_working' => true])
            ->orderBy('day_date')
            ->limit($this->overallDaysNumber)
            ->all();

        $controlCalendarDay = array_pop($days);
        return $controlCalendarDay->day_date;
    }

}
