<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "mission".
 *
 * @property int $id
 * @property string $name
 * @property string $date_start
 * @property string $date_end
 * @property int $country_id
 * @property int $region_id
 * @property string $city
 * @property string $order
 * @property string $target
 * @property string $iogv_id        // iogv_id пользователя, создавшего Командировку
 * @property int $duty_man_id
 * @property int $organization_id

 *
 * @property Country $country
 * @property Employee $dutyMan
 * @property Iogv $iogv
 * @property Region $region
 * @property boolean $visible
 * @property MissionAgreement[] $missionAgreements
 * @property MissionEmployee[] $missionEmployees
 */
class Mission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mission';
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
            [['name', 'country_id', 'order', 'iogv_id', 'date_start', 'date_end', 'organization_id', 'duty_man_id'], 'required'],
            [['name', 'target', 'iogv_id', 'city'], 'string'],
            [['date_start', 'date_end'], 'safe'],
            [['visible'], 'boolean'],
            [['country_id', 'region_id', 'duty_man_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['visible'], 'default', 'value' => true],
            [['country_id', 'region_id', 'duty_man_id', 'created_at', 'updated_at', 'organization_id'], 'integer'],
            [['order'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['duty_man_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['duty_man_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['region_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата окончания',
            'country_id' => 'Страна',
            'region_id' => 'Регион',
            'city' => 'Город',
            'order' => 'Номер приказа',
            'target' => 'Цель',
            'organization_id' => 'ИОГВ',
            'duty_man_id' => 'Ответственный за предоставление отчета',
        ];
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
    public function getDutyMan()
    {
        return $this->hasOne(Employee::className(), ['id' => 'duty_man_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*
    public function getMissionAgreements()
    {
        return $this->hasMany(MissionAgreement::className(), ['mission_id' => 'id']);
    }
    */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMissionEmployees()
    {
        return $this->hasMany(MissionEmployee::className(), ['mission_id' => 'id']);
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMissionResults()
    {
        return $this->hasMany(MissionResult::className(), ['mission_id' => 'id']);
    }


    /**
     * @inheritdoc
     * @return MissionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MissionQuery(get_called_class());
    }
}
