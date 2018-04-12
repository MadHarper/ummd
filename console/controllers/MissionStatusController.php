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



        // и смена статусов командировок
        $besedaStatusService = new BesedaStatusService();

        $besedas = Beseda::find()
                        ->where(['status' => [BesedaStatusService::STATUS_PROJECT, BesedaStatusService::STATUS_IN_ACTION]])->all();

        if($besedas){
            foreach ($besedas as $beseda){
                $besedaStatusService->checkAndChangeStatus($beseda);
            }
        }

    }
}