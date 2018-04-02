<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\base\MissionBase;
use frontend\core\services\MissionStatusService;

class MissionStatusController extends Controller
{

    public function actionIndex()
    {
        $today = date('Y-m-d');
        $missions = MissionBase::find()
                        ->where(['<=', 'date_start', $today])
                        ->andWhere(['>=', 'date_end', $today])
                        ->andWhere(['status' => MissionStatusService::STATUS_AGREDD])
                        ->all();


        foreach ($missions as $mission){
            $mission->status = MissionStatusService::STATUS_IN_ACTION;
            $mission->save(false);
        }

        $missions = MissionBase::find()
                        ->andWhere(['<', 'date_end', $today])
                        ->andWhere(['status' => MissionStatusService::STATUS_IN_ACTION])
                        ->all();

        foreach ($missions as $mission){
            $mission->status = MissionStatusService::STATUS_DONE;
            $mission->save(false);
        }
    }
}