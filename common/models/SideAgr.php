<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "side_agr".
 *
 * @property int $id
 * @property string $desc
 * @property string $subdivision
 * @property int $agreement_id
 * @property int $org_id
 * @property int $employee_id
 *
 * @property Agreement $agreement
 * @property Employee $employee
 * @property Organization $org
 */
class SideAgr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'side_agr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc', 'subdivision'], 'string'],
            [['agreement_id', 'org_id'], 'required'],
            [['agreement_id', 'org_id', 'employee_id'], 'default', 'value' => null],
            [['agreement_id', 'org_id', 'employee_id'], 'integer'],
            [['agreement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agreement::className(), 'targetAttribute' => ['agreement_id' => 'id']],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
            [['org_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['org_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'desc' => 'Служебные пометки',
            'subdivision' => 'Ответственное подразделение',
            'agreement_id' => 'Соглашение',
            'org_id' => 'Наименование организации',
            'employee_id' => 'ФИО представителя организации',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgreement()
    {
        return $this->hasOne(Agreement::className(), ['id' => 'agreement_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(Organization::className(), ['id' => 'org_id']);
    }
}
