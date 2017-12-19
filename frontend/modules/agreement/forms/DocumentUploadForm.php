<?php

namespace frontend\modules\agreement\forms;

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
            [['documentFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'docx'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $base_name = $this->documentFile->baseName;
            $ext = $this->documentFile->extension;
            $newName = uniqid();

            if($this->documentFile->saveAs(Yii::getAlias('@common/seafile/docs/') . $newName . '.' . $ext)){
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