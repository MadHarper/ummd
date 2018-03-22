<?php

namespace frontend\core\forms;

use Yii;
use yii\base\Model;


class DocUploadForm extends Model
{

    public $document;
    public $type;
    public $date;
    public $name;
    public $note;

    private $base_name;
    private $ext;
    private $newName;

    public function rules()
    {
        return [
            [['document'], 'file', 'skipOnEmpty' => false, 'extensions' => 'docx, doc, jpeg, jpg, gif, png'],
            [['type', 'name'], 'required'],
            [['type'], 'integer'],
            [['name', 'note'], 'string'],
            [['date'], 'safe']
        ];
    }


    public function attributeLabels()
    {
        return [
            'document'      => 'Файл',
            'type'          => 'Тип документа',
            'name'          => 'Наименование',
            'date'          => 'Дата документа',
            'note'          => 'Примечание',
        ];
    }

    public function upload()
    {
        $this->type = (int)$this->type;

        if ($this->validate()) {
            $this->base_name    = $this->document->baseName;
            $this->ext          = $this->document->extension;
            $this->newName      = uniqid();

            $uploadPathString = Yii::$app->params['sea']['upload_path'];
            if($this->document->saveAs(Yii::getAlias($uploadPathString) . $this->newName . '.' . $this->ext)){
                return true;
            }
        }

        return false;
    }


    public function getBaseName()
    {
        return $this->base_name;
    }

    public function getExt()
    {
        return $this->ext;
    }

    public function getNewName()
    {
        return $this->newName;
    }


}