<?php

namespace common\models;

use Yii;
use frontend\core\interfaces\WithDocumentInterface;
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
}
