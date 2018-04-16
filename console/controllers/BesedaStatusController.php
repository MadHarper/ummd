<?php

namespace console\controllers;

use common\models\base\BesedaBase;
use yii\console\Controller;
use frontend\core\services\BesedaStatusService;

class BesedaStatusController extends Controller
{

    public function actionIndex()
    {
        $today = date('Y-m-d');


        // и смена статусов бесед
        $besedaStatusService = new BesedaStatusService();

        $besedas = BesedaBase::find()
            ->where(['status' => [BesedaStatusService::STATUS_SENDING_IN_KVS, BesedaStatusService::STATUS_IN_ACTION]])->all();

        if($besedas){
            foreach ($besedas as $beseda){
                $besedaStatusService->checkAndChangeStatus($beseda);
            }
        }


        //Теперь проверяем просроченный статус (галочка)
        $besedes = BesedaBase::find()->where(['report_date' => NULL])->andWhere(['<', 'control_date', $today])->andWhere(['report_overdue' => false])->all();
        if($besedes){
            foreach ($besedes as $beseda){
                $beseda->report_overdue = true;
                $beseda->save(false);
            }
        }

        $otherBesedes = BesedaBase::find()->where(['not', ['report_date' => null]])->andWhere(['report_overdue' => true])->all();
        if($otherBesedes){
            foreach ($otherBesedes as $bes){
                $bes->report_overdue = false;
                $bes->save(false);
            }
        }
    }
}