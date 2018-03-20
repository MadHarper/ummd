<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property string $model
 * @property int $model_id
 * @property string $content
 * @property string $description
 * @property string $origin_name
 * @property string $sea_name
 * @property string $link
 * @property bool $visible
 * @property int $created_at
 * @property int $updated_at
 * @property int $doc_type_id
 * @property int $doc_date
 * @property int $name
 * @property int $note
 */
class Document extends \yii\db\ActiveRecord
{

    const STATUS_NOT_PROCESSED = 1;
    const STATUS_YES_PROCESSED = 2;



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return date('U'); },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'model_id'], 'required'],
            [['model_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['model_id', 'created_at', 'updated_at', 'status', 'doc_type_id'], 'integer'],
            [['content', 'description', 'iogv_id', 'type', 'name', 'note'], 'string'],
            [['visible'], 'boolean'],
            [['model', 'origin_name', 'sea_name', 'link'], 'string', 'max' => 255],
            [['doc_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'model_id' => 'Model ID',
            'content' => 'Контент',
            'description' => 'Описание',
            'origin_name' => 'Название',
            'sea_name' => 'Sea Name',
            'link' => 'Ссылка',
            'visible' => 'Доступен',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'status' => 'Статус',
            'iogv_id' => 'ID подразделения'
        ];
    }


    public function getMasterModel()
    {
        switch ($this->model) {
            case Agreement::class:
                return $this->hasOne(Agreement::className(), ['id' => 'model_id']);
        }

    }


    public function getType()
    {
        return $this->hasOne(DocumentType::className(), ['id' => 'doc_type_id']);
    }
}
