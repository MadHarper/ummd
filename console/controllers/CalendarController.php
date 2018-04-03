<?php

namespace console\controllers;

use yii\console\Controller;
use common\services\Tokens;
use common\services\DbCalendarRefresh;
use Yii;

class CalendarController extends Controller
{

    public function actionIndex()
    {
        $tokerService = new Tokens();
        $aistoken = $tokerService->getAccessToken();

        $calendarRefresh = new DbCalendarRefresh($aistoken);
        $calendarRefresh->job();

        return;
    }
}