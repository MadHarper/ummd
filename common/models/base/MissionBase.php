<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\Country;
use common\models\Employee;
use common\models\Organization;
use common\models\Region;
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
class MissionBase extends \yii\db\ActiveRecord
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
            ['status', 'default', 'value' => 1],
            ['status', 'filter', 'filter' => 'intval'],
            [['name', 'country_id', 'order', 'iogv_id', 'date_start', 'date_end', 'organization_id', 'duty_man_id'], 'required'],
            [['name', 'target', 'iogv_id', 'city', 'cityName', 'notes'], 'string'],
            [['date_start', 'date_end', 'agreementsArray'], 'safe'],
            [['visible'], 'boolean'],
            [['country_id', 'region_id', 'duty_man_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['visible'], 'default', 'value' => true],
            [['country_id', 'region_id', 'duty_man_id', 'created_at', 'updated_at', 'organization_id', 'city_id', 'status'], 'integer'],
            [['order'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['duty_man_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['duty_man_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['region_id' => 'id']],
            [['date_start', 'date_end'], 'validateDate'],
        ];
    }


    public function validateDate(){
        if ($this->date_start > $this->date_end){
            $this->addError('date_begin', '"Проверьте дату окончания"');
            $this->addError('date_end', '"Дата окончания", не может быть раньше "даты начала"');
        }
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
            'cityName' => 'Город',
            'city_id' => 'Город',
            'notes' => 'Служебные пометки',
            'status' => 'Статус командировки'
        ];
    }




}
