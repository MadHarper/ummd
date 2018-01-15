<?php

namespace common\services\jobs;

use Yii;
use yii\base\BaseObject;
use common\models\Document;
use common\services\DocxReaderService;

class DocumentSaveJob extends BaseObject implements \yii\queue\JobInterface
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
        }

        $link = $seaFileService->uploadToSea($directoryItemName, $this->tempPath, $this->newName);
        // Распарсим docx в текст
        $reader = new DocxReaderService($this->tempPath);
        $text = $reader->convertToText();

        $document = Document::find()->where(['id' => $this->document_id])->one();
        $document->link = $link;
        $document->content = $text;
        $document->status = Document::STATUS_YES_PROCESSED;
        $document->save();

        //удалим файл с нашего сервера
        unlink($this->tempPath);
    }
}