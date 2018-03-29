<?php

namespace frontend\core\services;

use common\models\Agreement;
use common\models\DocumentType;
use Yii;
use frontend\models\Document;
use frontend\core\interfaces\WithDocumentInterface;
use common\services\jobs\DocumentSaveJob;
use common\services\jobs\PictureSaveJob;
use frontend\core\forms\DocUploadForm;
use frontend\core\helpers\DocTypeHelper;

class DocPrepareSaveService
{

    private $model;
    private $userId;
    private $uploadPath;
    private $form;

    public function __construct(WithDocumentInterface $model, $userId, $uploadPath, DocUploadForm $form)
    {
        $this->model        = $model;
        $this->userId       = $userId;
        $this->uploadPath   = $uploadPath;
        $this->form         = $form;
    }


    public function doPrepare()
    {
        $document               = new Document();
        $document->doc_type_id  = $this->form->type;
        $document->doc_date     = $this->form->date;
        $document->name         = $this->form->name;
        $document->note         = $this->form->note;

        $document->origin_name  = $this->form->getBaseName();
        $document->sea_name     = $this->form->getNewName();
        $document->type         = $this->form->getExt();

        $document->model        = $this->model::className();
        $document->model_id     = $this->model->id;
        $document->iogv_id      = $this->model->getIogvId();
        $document->user_id      = $this->userId;

        $document->status       = Document::STATUS_NOT_PROCESSED;


        if(!$document->save()){
            return false;
        }

        if( DocumentType::TYPE_MEROPRIYATIE === $document->doc_type_id  && Agreement::className() === $document->model ){
            $this->changeAgreementPlan();
        }


        // и помещаем в очередь в зависимости от раширения (будем парсить текст или нет)
        if( in_array($document->type, DocTypeHelper::PARSING_EXTENTION) ){

            Yii::$app->queue->push(new DocumentSaveJob([
                'tempPath'          => Yii::getAlias($this->uploadPath) . $document->sea_name . '.' . $document->type,
                'document_id'       => $document->id,
                'newName'           => $document->sea_name . '.' . $document->type,
                'iogv_id'           => $this->model->getIogvId(),
            ]));

        }else{

            Yii::$app->queue->push(new PictureSaveJob([
                'tempPath'          => Yii::getAlias($this->uploadPath) . $document->sea_name . '.' . $document->type,
                'document_id'       => $document->id,
                'newName'           => $document->sea_name . '.' . $document->type,
                'iogv_id'           => $this->model->getIogvId()
            ]));
        }

        return true;
    }


    // изменяет признак наличия плана у соглашение (поле plan)
    private function changeAgreementPlan()
    {
        $this->model->meropriatie = true;
        $this->model->save();
    }
}
