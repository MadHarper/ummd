<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use frontend\core\interfaces\WithDocumentInterface;
use frontend\core\services\CheckOrAddCityService;
use frontend\core\services\MissionStatusService;

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
class Mission extends \common\models\base\MissionBase implements WithDocumentInterface
{

    public $_agreements;

    public $cityName;



    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {

            // если страна не Россия, то удалим регион
            if($this->country_id != Country::RUSSIA_ID){
                $this->region_id = NULL;
            }

            // если пришел текстовое поле имя города, то проверим или добавим его в справочник городов
            if(isset($this->cityName)){
                $checkCityService = new CheckOrAddCityService();
                $city_id = $checkCityService->check($this->cityName);
                if($city_id){
                    $this->city_id = $city_id;
                }
            }

            return true;
        }
        return false;
    }


    public function afterFind(){
        parent::afterFind();

        if(isset($this->city_id)){
            $c = City::find()->where(['id' => $this->city_id])->one();
            if($c){
                $this->cityName = $c->name;
            }
        }
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

    public function getTown()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMissionAgreements()
    {
        return $this->hasMany(MissionAgreement::className(), ['mission_id' => 'id']);
    }

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


    public function getAgreementsArray()
    {
        if ($this->_agreements === null) {
            $this->_agreements = $this->getMissionAgreements()
                                        ->select('agreement_id')
                                        ->where(['mission_id' => $this->id])
                                        ->orderBy("agreement_id")
                                        ->column();
        }

        return $this->_agreements;
    }

    public function setAgreementsArray($value)
    {
        if(empty($value)){
            $this->_agreements = [];
        }else{
            $this->_agreements = (array)$value;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->updateAgreements();
        parent::afterSave($insert, $changedAttributes);
    }


    private function updateAgreements()
    {
        $currentAgreementsIds = $this->getMissionAgreements()->select('agreement_id')->column();
        $newAgreementsIds = $this->getAgreementsArray();

        $new = [];
        foreach ($newAgreementsIds as $item){
            $new[] = (int)$item;
        }

        foreach (array_filter(array_diff($new, $currentAgreementsIds)) as $agId) {
            if ($ag = Agreement::findOne($agId)) {
                $missionAgreement = new MissionAgreement(['mission_id' => $this->id, 'agreement_id' => $agId]);
                $missionAgreement->save();
            }
        }

        foreach (array_filter(array_diff($currentAgreementsIds, $new)) as $oldId) {
            if($ma = MissionAgreement::find()->where(['mission_id' => $this->id, 'agreement_id' => $oldId])->one()){
                $ma->delete();
            }
        }
    }


    public function getIogvId()
    {
        return $this->iogv_id;
    }

    public function getEmployesEntity()
    {
        return $this->hasMany(Employee::className(), ['id' => 'employee_id'])
            ->viaTable('mission_employee', ['mission_id' => 'id']);
    }
}
