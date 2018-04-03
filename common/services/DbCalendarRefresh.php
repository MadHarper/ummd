<?php

namespace common\services;

use Yii;
use common\models\Calendar;


class DbCalendarRefresh
{
    private $aistoken;
    private $calendarServiceUrl;

    public function __construct(string $aistoken)
    {
        $this->aistoken = $aistoken;
        $this->calendarServiceUrl = Yii::$app->params['calendarService'];
    }

    public function job()
    {
        $currentYear = date('Y');
        $urlString = str_replace("%YEAR%", $currentYear, $this->calendarServiceUrl);
        $this->getNewCalendar($urlString);

        $nextYear = date('Y', strtotime('+1 year'));
        $urlString = str_replace("%YEAR%", $nextYear, $this->calendarServiceUrl);
        $this->getNewCalendar($urlString);
    }


    //Получаем производственный календарь
    private function getNewCalendar($urlString)
    {
        $header = array();
        $header[] = 'Content-type: application/json; charset=UTF-8';
        $header[] = "Authorization: Bearer $this->aistoken";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $urlString);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

        if(isset(\Yii::$app->params['proxySettings'])){
            $proxySettings = \Yii::$app->params['proxySettings'];
            curl_setopt($curl, CURLOPT_PROXY, $proxySettings['host']);
            curl_setopt($curl, CURLOPT_PROXYUSERPWD, $proxySettings['logpass']);
        }

        $data = curl_exec($curl);

        if (curl_errno($curl)) {
            Yii::error("Ошибка отправки уведомления сотруднику КВС" . curl_error($curl));
            return;
        }

        if(curl_error($curl))
        {
            Yii::error("Ошибка отправки уведомления сотруднику КВС" . curl_error($curl));
            return;
        }

        curl_close($curl);
        $data = json_decode($data);

        if(!$data || !$data->workCalendar){
            Yii::error("Не поступили данные от сервиса производственного календаря");
            return;
        }

        $this->refreshDbItems($data->workCalendar);
    }


    // освежаем календарь в DB
    private function refreshDbItems($newCalendarDays)
    {
        foreach ($newCalendarDays as $day){
            $currentDbDay = Calendar::find()->where(['day_date' => $day->date])->one();
            if($currentDbDay){
                if(0 === $day->workingHours && $day->is_working ){
                    $currentDbDay->is_working = false;
                    $currentDbDay->save();
                }
                if(0 !== $day->workingHours && !$day->is_working ){
                    $currentDbDay->is_working = true;
                    $currentDbDay->save();
                }
            }else{
                $currentDbDay = new Calendar();
                $currentDbDay->day_date   = $day->date;
                $currentDbDay->is_working = $day->workingHours === 0 ? false : true;
                $currentDbDay->save();
            }
        }
    }

}