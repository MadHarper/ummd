<?php

namespace common\models;

use Yii;
use frontend\core\interfaces\WithDocumentInterface;
use frontend\core\services\BesedaStatusService;
/**
 * This is the model class for table "beseda".
 *
 * @property int $id
 * @property string $theme
 * @property string $target
 * @property int $created_at
 * @property int $updated_at
 * @property string $date_start
 * @property string $date_start_time
 * @property int $iniciator_id
 * @property string $report_date
 * @property string $control_date
 * @property int $status
 * @property string $notes
 * @property int $iogv_id
 *
 * @property Organization $iniciator
 */
class Beseda extends \common\models\base\BesedaBase implements WithDocumentInterface
{

    public $_agreements;


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIniciator()
    {
        return $this->hasOne(Organization::className(), ['id' => 'iniciator_id']);
    }

    /**
     * {@inheritdoc}
     * @return BQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BQuery(get_called_class());
    }

    public function getIogvId()
    {
        return $this->iogv_id;
    }

    public function getStatusString()
    {
         return BesedaStatusService::STATUS_LIST[$this->status];
    }

    public function getGeneralName(){
        return $this->theme;
    }

    public function getHasAgreements()
    {
        return $this->hasMany(Agreement::className(), ['id' => 'agreement_id'])
            ->viaTable('beseda_agreement', ['beseda_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBesedaAgreements()
    {
        return $this->hasMany(BesedaAgreement::className(), ['beseda_id' => 'id']);
    }



    public function getAgreementsArray()
    {
        if ($this->_agreements === null) {
            $this->_agreements = $this->getBesedaAgreements()
                ->select('agreement_id')
                ->where(['beseda_id' => $this->id])
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
        $currentAgreementsIds = $this->getBesedaAgreements()->select('agreement_id')->column();
        $newAgreementsIds = $this->getAgreementsArray();

        $new = [];
        foreach ($newAgreementsIds as $item){
            $new[] = (int)$item;
        }

        foreach (array_filter(array_diff($new, $currentAgreementsIds)) as $agId) {
            if ($ag = Agreement::findOne($agId)) {
                $besedaAgreement = new BesedaAgreement(['beseda_id' => $this->id, 'agreement_id' => $agId]);
                $besedaAgreement->save();
            }
        }

        foreach (array_filter(array_diff($currentAgreementsIds, $new)) as $oldId) {
            if($ma = BesedaAgreement::find()->where(['beseda_id' => $this->id, 'agreement_id' => $oldId])->one()){
                $ma->delete();
            }
        }
    }

}
