<?php

namespace frontend\core\services;

use common\models\City;

class CheckOrAddCityService
{

    public function check($cityName)
    {
        $id = false;
        $baseCity = City::find()->where(['ilike', 'name', $cityName, false])->one();

        if($baseCity){
            return $baseCity->id;
        }

        $newCity = new City();
        $newCity->name = $cityName;
        if($newCity->save()){
            return $newCity->id;
        }

        return $id;
    }
}
