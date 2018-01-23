<?php

namespace frontend\forms;

use common\models\Agreement;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\Json;


class DocumentUploadForm extends Model
{

    public $documentFile;

    public function rules()
    {
        return [
            [['documentFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'docx, jpeg, jpg, gif, png'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $base_name = $this->documentFile->baseName;
            $ext = $this->documentFile->extension;
            $newName = uniqid();

            $uploadPathString = Yii::$app->params['sea']['upload_path'];
            if($this->documentFile->saveAs(Yii::getAlias($uploadPathString) . $newName . '.' . $ext)){
                return [
                   'base_name'  => $base_name,
                   'ext'        => $ext,
                   'newName'    => $newName
                ];
            }
        }

        return false;
    }
}