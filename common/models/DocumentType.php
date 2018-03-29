<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "document_type".
 *
 * @property int $id
 * @property string $name
 * @property bool $visible
 *
 * @property Document[] $documents
 */
class DocumentType extends \yii\db\ActiveRecord
{
    // при развертывании приложения создается запись "План мероприятия" под id == 1
    const TYPE_MEROPRIYATIE = 1;



    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visible'], 'boolean'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'visible' => 'Видимость',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['doc_type_id' => 'id']);
    }
}
