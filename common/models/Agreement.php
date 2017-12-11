<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "agreement".
 *
 * @property int $id
 * @property int $status
 * @property string $name
 * @property string $date_start
 * @property string $date_end
 * @property int $iogv_id
 * @property string $desc
 * @property int $created_at
 * @property int $updated_at
 *
 * @property SideAgr[] $sideAgrs
 */
class Agreement extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agreement';
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
            [['status', 'name'], 'required'],
            [['status', 'iogv_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'iogv_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'desc'], 'string'],
            [['date_start', 'date_end'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'name' => 'Наименование',
            'date_start' => 'Дата заключения',
            'date_end' => 'Дата окончания   ',
            'iogv_id' => 'Iogv ID',
            'desc' => 'Служебные пометки',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSideAgrs()
    {
        return $this->hasMany(SideAgr::className(), ['agreement_id' => 'id']);
    }


    public function getShortName(){
        $short = $this->name;
        if(strlen($short) > 70){
            $short = mb_substr($short, 0, 70);
            $short .= "...";
        }

        return $short;
    }
}
