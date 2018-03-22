<?php

namespace frontend\core\services;

use Yii;
use frontend\models\Document;


class DocPrepareSaveService
{

    public function prepareDocSave($modelClass, $agreementId)
    {
        $document               = new Document();
        $document->model        = $modelClass;
        $document->model_id     = $agreement->id;
        $document->iogv_id      = $agreement->iogv_id;
        $document->user_id      = Yii::$app->user->id;
        $document->origin_name   = $res['base_name'];
        $document->sea_name     = $res['newName'];
        $document->status       = Document::STATUS_NOT_PROCESSED;
        $document->type         = $res['ext'];
        $document->save();
    }

}
