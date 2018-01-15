<?php

namespace common\services\jobs;

use Yii;
use yii\base\BaseObject;
use common\models\Document;
use common\services\DocxReaderService;

class FileRemoveJob extends BaseObject implements \yii\queue\JobInterface
{
    public $path;

    public function execute($queue)
    {
        if(!file_exists($this->path)){
            Yii::error("не найден скачанный файл: " . $this->path);
        }

        if(file_exists($this->path)){
            unlink($this->path);
        }else{
            $path = iconv('utf-8', 'cp1251', $this->path);
            if(file_exists($path)){
                unlink($path);
            }
        }
    }
}