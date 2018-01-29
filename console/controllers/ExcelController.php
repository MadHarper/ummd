<?php

namespace console\controllers;

use yii\console\Controller;
use PHPExcel_IOFactory;
use yii\db\Exception;
use Yii;
use common\models\Country;

class ExcelController extends Controller
{

    public function actionIndex()
    {
        if(Country::find()->count() == 0){
            try{
                $Excel = PHPExcel_IOFactory::load(Yii::getAlias('@common/excel/country.xlsx'));
            }catch (Exception $exception){
                var_dump($exception);
                return;
            }

            $Start = 1;
            for ($i= $Start; $i <= 251; $i++)
            {
                $Row = new \stdClass();
                $Row->id = $i;

                $Row->data = $Excel->getActiveSheet()->getCell('B'.$i )->getValue();
                if($Row->data){
                    $country = new Country();
                    $country->name = $Row->data;
                    $country->save();
                }

                if($Row->data == null) continue;
            }

            echo "ok!";
        }

        echo "nothing!";

        return;
    }
}