<?php

namespace frontend\core\services;


use common\models\Beseda;
use common\models\Calendar;

class BesedaStatusService
{
    const STATUS_PROJECT        = 1;
    const STATUS_SENDING_IN_KVS = 2;
    const STATUS_IN_ACTION      = 3;
    const STATUS_DONE           = 4;
    const STATUS_CANCELLED      = 5;


    const STATUS_LIST = [
        self::STATUS_PROJECT        => 'Проект',
        self::STATUS_SENDING_IN_KVS => 'Направлено в КВС',
        self::STATUS_IN_ACTION      => 'Проводится',
        self::STATUS_DONE           => 'Состоялась',
        self::STATUS_CANCELLED      => 'Отменена',
    ];


    public function getAvailableStatuses(int $currentStatus)
    {

        if(!array_key_exists($currentStatus, self::STATUS_LIST)){
            throw new \DomainException("Ошибка. Попытка присвоить несуществующий статус");
        }

        switch ($currentStatus) {
            case self::STATUS_PROJECT :
                return [    self::STATUS_PROJECT,
                            self::STATUS_SENDING_IN_KVS,
                            self::STATUS_CANCELLED
                        ];
                break;

            case self::STATUS_SENDING_IN_KVS :
                return [    self::STATUS_SENDING_IN_KVS,
                            self::STATUS_IN_ACTION,
                            self::STATUS_CANCELLED
                ];
                break;


            case self::STATUS_IN_ACTION :
                return [    self::STATUS_IN_ACTION,
                            self::STATUS_DONE,
                            self::STATUS_CANCELLED
                        ];
                break;

            case self::STATUS_DONE :
                return [    self::STATUS_DONE,
                            self::STATUS_CANCELLED ];
                break;

            case self::STATUS_CANCELLED :
                return [    self::STATUS_CANCELLED ];
                break;
        }
    }


    public function getStatusList(array $availableStatuses)
    {
        return array_filter(self::STATUS_LIST,
            function($key) use($availableStatuses){
                return in_array($key, $availableStatuses);
            },
            ARRAY_FILTER_USE_KEY);
    }


    public function getStatusListFromCurrent(int $currentStatus)
    {
        $availableStatuses = $this->getAvailableStatuses($currentStatus);
        $statusList = $this->getStatusList($availableStatuses);

        return $statusList;
    }


/*
    public function checkAndChangeStatus(Beseda $model)
    {
        $today = date('Y-m-d');

        if (self::STATUS_PROJECT === (int)$model->status && $model->date_start === $today) {
            $model->status = self::STATUS_IN_ACTION;
            $model->save(false);
        }


        if($today < $model->date_start){
            $s = 25;
        }

        if(self::STATUS_IN_ACTION === (int)$model->status && $today < $model->date_start){
            $model->status = self::STATUS_DONE;
            $model->save(false);
        }

        return;
    }


*/


    public function checkControlDay(Beseda $model)
    {

        $days = Calendar::find()
            ->where(['>', 'day_date', $model->date_start])
            ->andWhere(['is_working' => true])
            ->orderBy('day_date')
            ->limit(5)
            ->all();

        $lastDay = array_pop($days);
        $model->control_date = $lastDay->day_date;

        $today = date('Y-m-d');
        if(!$model->report_date && $today > $model->control_date){
            $model->report_overdue = true;
        }

        if(!$model->report_date && $today < $model->control_date){
            $model->report_overdue = false;
        }

        $model->save(false);
    }





    public function checkAndChangeStatus(Beseda $model)
    {
        $today = date('Y-m-d');

        if (self::STATUS_SENDING_IN_KVS === (int)$model->status && $model->date_start === $today) {
            $model->status = self::STATUS_IN_ACTION;
            $model->save(false);
        }

        if(self::STATUS_IN_ACTION === (int)$model->status && $today > $model->date_start){
            $model->status = self::STATUS_DONE;
            $model->save(false);
        }

        return;
    }

}


