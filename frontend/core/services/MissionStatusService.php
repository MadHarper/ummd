<?php

namespace frontend\core\services;


use common\models\Mission;

class MissionStatusService
{
    const STATUS_PROJECT        = 1;
    const STATUS_AGREDD         = 2;
    const STATUS_NOT_AGREED     = 3;
    const STATUS_IN_ACTION      = 4;
    const STATUS_DONE           = 5;
    const STATUS_CANCELLED      = 6;

    const STATUS_LIST = [
        self::STATUS_PROJECT        => 'Проект',
        self::STATUS_AGREDD         => 'Согласована',
        self::STATUS_NOT_AGREED     => 'Не согласована',
        self::STATUS_IN_ACTION      => 'Выполняется',
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
                return [ self::STATUS_PROJECT,
                         self::STATUS_AGREDD,
                         self::STATUS_NOT_AGREED,
                         self::STATUS_CANCELLED ];
                break;

            case self::STATUS_AGREDD :
                return [ self::STATUS_AGREDD,
                         self::STATUS_IN_ACTION,
                         self::STATUS_CANCELLED ];
                break;

            case self::STATUS_NOT_AGREED :
                return  [ self::STATUS_NOT_AGREED ];
                break;

            case self::STATUS_IN_ACTION :
                return [ self::STATUS_IN_ACTION,
                         self::STATUS_DONE,
                         self::STATUS_CANCELLED ];
                break;

            case self::STATUS_DONE :
                return [ self::STATUS_DONE,
                         self::STATUS_CANCELLED ];
                break;

            case self::STATUS_CANCELLED :
                return [ self::STATUS_CANCELLED ];
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

    public function checkNewStatus(Mission $model)
    {
         $availableStatuses = $this->getAvailableStatuses($model->oldAttributes['status']);
         if(!in_array($model->status, $availableStatuses)){
             throw new \DomainException("Ошибка. Попытка присвоить некорректный статус");
         }

         return true;
    }


    public function checkAndChangeStatus(Mission $model)
    {
        $today = date('Y-m-d');

        if($model->date_end < $today && MissionStatusService::STATUS_IN_ACTION === $model->status ){
            $model->status = MissionStatusService::STATUS_DONE;
            $model->save();
            return;
        }

        if($model->date_start >= $today && $today <= $model->date_end && MissionStatusService::STATUS_AGREDD === $model->status ){
            $model->status = MissionStatusService::STATUS_IN_ACTION;
            $model->save();
        }

        return;
    }
}


