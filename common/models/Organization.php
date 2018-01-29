<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "organization".
 *
 * @property int $id
 * @property string $name
 * @property string $contact
 * @property int $country_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Employee[] $employees
 * @property Country $country
 * @property SideAgr[] $sideAgrs
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organization';
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
            [['name'], 'required'],
            [['contact'], 'string'],
            [['country_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['country_id', 'created_at', 'updated_at', 'prev_id', 'main_id'], 'integer'],
            [['history'], 'default', 'value' => false],
            [['prev_id'], 'default', 'value' => null],
            [['iogv', 'history'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'name'          => 'Наименование',
            'contact'       => 'Контакт',
            'country_id'    => 'Страна',
            'created_at'    => 'Создано',
            'updated_at'    => 'Обновлено',
            'iogv'          => 'ИОГВ',
            'history'       => 'Историческая'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['organization_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSideAgrs()
    {
        return $this->hasMany(SideAgr::className(), ['org_id' => 'id']);
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
