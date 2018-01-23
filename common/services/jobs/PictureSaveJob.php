<?php

namespace common\services\jobs;

use Yii;
use yii\base\BaseObject;
use common\models\Document;
use common\services\DocxReaderService;

class PictureSaveJob extends BaseObject implements \yii\queue\JobInterface
{
    //public $originName;
    public $tempPath;
    //public $masterClass;
    //public $model_id;
    public $iogv_id;
    //public $user_id;
    public $newName;
    public $document_id;

    public function execute($queue)
    {
        $seaFileService = Yii::$app->seaFileService;

        // потому что без нуля вначале глючит
        $directoryItemName = '0' . $this->iogv_id;

        if(!$seaFileService->checkOrCreateDir($directoryItemName)){
            //Todo: генерируем исключение, пишем в лог об ошибкеж
            Yii::error('Ошибка метода checkOrCreateDir ' . self::className());
        }

        $link = $seaFileService->uploadToSea($directoryItemName, $this->tempPath, $this->newName);


        $document = Document::find()->where(['id' => $this->document_id])->one();

        if(isset(Yii::$app->params['cutSeafileDomain']) && Yii::$app->params['cutSeafileDomain']){
            $offset = strpos($link, "/f/");
            $document->link = mb_substr($link, $offset);
        }else{
            $document->link = $link;
        }

        $document->status = Document::STATUS_YES_PROCESSED;
        $document->save();

        //удалим файл с нашего сервера
        unlink($this->tempPath);
    }
}