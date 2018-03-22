<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\services\jobs\TorisAgreementMessageJob;
use yii\helpers\Url;
use frontend\core\interfaces\WithDocumentInterface;

/**
 * This is the model class for table "agreement".
 *
 * @property int $id
 * @property int $status
 * @property int $state
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
class Agreement extends ActiveRecord implements WithDocumentInterface
{


    const STATUS_PROJECT = 1;
    const STATUS_DONE = 2;

    const STATE_ACTIVE = 1;
    const STATE_SUSPENDED = 2;



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
            ['status', 'default', 'value' => self::STATUS_PROJECT],
            [['name'], 'required'],
            [['status', 'iogv_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'created_at', 'updated_at', 'state'], 'integer'],
            [['name', 'desc', 'iogv_id',], 'string'],
            [['date_start', 'date_end'], 'safe'],
            ['status', 'filter', 'filter' => 'intval'],
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
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
            'state' => 'Состояние'
        ];
    }


    public static function find()
    {
        return new AgreementQuery(get_called_class());
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_PROJECT => 'Проект',
            self::STATUS_DONE => 'Заключено'
        ];
    }

    public function getStatusToString()
    {
        $arr = self::getStatusList();
        return $arr[$this->status];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSideAgrs()
    {
        return $this->hasMany(SideAgr::className(), ['agreement_id' => 'id']);
    }

    public function getCountries(){

        if($sides = $this->sideAgrs){
            $result = [];
            foreach ($sides as $side){
                $result[] = $side->org->country;
            }

            return $result;
        }

        return false;
    }


    public function getShortName(){
        $short = $this->name;
        if(strlen($short) > 70){
            $short = mb_substr($short, 0, 70);
            $short .= "...";
        }

        return $short;
    }


    public function afterSave($insert, $changedAttributes){
        if(isset($changedAttributes['status']) && $changedAttributes['status'] == self::STATUS_PROJECT && $this->status == self::STATUS_DONE ){
            // Помещаем в очередь
            Yii::$app->queue->push(new TorisAgreementMessageJob([
                'link'          => Url::to(['/agreement/default/view', 'id' => $this->id], true),
                'aistoken'      => Yii::$app->user->identity->aistoken,
            ]));
        }

        parent::afterSave($insert, $changedAttributes);
    }


    public static function getStateList()
    {
        return [
            self::STATE_ACTIVE      => "Действующее соглашение",
            self::STATE_SUSPENDED   => "Соглашение приостановлено"
        ];
    }

    public function getStateToString()
    {
        $arr = self::getStateList();
        return $arr[$this->state];
    }

    public function getIogvId()
    {
        return $this->iogv_id;
    }
}
