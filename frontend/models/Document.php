<?php

namespace frontend\models;

use common\models\Agreement;


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
 */
class Document extends \common\models\Document
{



    /**
     * @inheritdoc
     * @return DocumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DocumentQuery(get_called_class());
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => $this->model === Agreement::class ? 'Соглашение' : '',
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


}
