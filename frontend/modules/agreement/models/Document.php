<?php

namespace frontend\modules\agreement\models;




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
}
