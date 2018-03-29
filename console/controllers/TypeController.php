<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\DocumentType;

class TypeController extends Controller
{

    public function actionIndex()
    {
         $dt = new DocumentType();
         $dt->name = "План Мероприятий";
         $dt->save();
    }
}