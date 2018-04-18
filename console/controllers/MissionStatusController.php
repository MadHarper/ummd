<?php

namespace console\controllers;

use common\models\Beseda;
use yii\console\Controller;
use common\models\base\MissionBase;
use frontend\core\services\MissionStatusService;
use frontend\core\services\BesedaStatusService;

class MissionStatusController extends Controller
{

    public function actionIndex()
    {
        $today = date('Y-m-d');

        $missions1 = MissionBase::find()
                        ->where(['<=', 'date_start', $today])
                        ->andWhere(['>=', 'date_end', $today])
                        ->andWhere(['status' => MissionStatusService::STATUS_AGREDD])
                        ->all();


        foreach ($missions1 as $mission){
            $mission->status = MissionStatusService::STATUS_IN_ACTION;
            $mission->save(false);
        }

        $missions2 = MissionBase::find()
                        ->andWhere(['<', 'date_end', $today])
                        ->andWhere(['status' => MissionStatusService::STATUS_IN_ACTION])
                        ->all();

        foreach ($missions2 as $mission){
            $mission->status = MissionStatusService::STATUS_DONE;
            $mission->save(false);
        }



        $missions3 = MissionBase::find()
                        ->where(['report_date' => NULL])
                        ->andWhere(['<', 'contol_date', $today])
                        ->all();

        foreach ($missions3 as $mission){
            $mission->report_overdue = true;
            $mission->save(false);
        }


    }
}