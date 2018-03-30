<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\DocumentType;

class TypeController extends Controller
{

    public function actionIndex()
    {
         $dt = DocumentType::find()->where(['id' => 1])->one();

         if(!$dt){
             $dt = new DocumentType();
         }

         $dt->name = "План Мероприятий";
         $dt->visible = true;
         $dt->save();
    }
}