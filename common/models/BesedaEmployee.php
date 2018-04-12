<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "beseda_employee".
 *
 * @property int $id
 * @property int $beseda_id
 * @property int $employee_id
 *
 * @property Beseda $beseda
 * @property Employee $employee
 */
class BesedaEmployee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'beseda_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['beseda_id', 'employee_id'], 'default', 'value' => null],
            [['beseda_id', 'employee_id'], 'integer'],
            [['beseda_id'], 'exist', 'skipOnError' => true, 'targetClass' => Beseda::className(), 'targetAttribute' => ['beseda_id' => 'id']],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'beseda_id' => 'Beseda ID',
            'employee_id' => 'Employee ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeseda()
    {
        return $this->hasOne(Beseda::className(), ['id' => 'beseda_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    /**
     * {@inheritdoc}
     * @return BesedaEmployeeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BesedaEmployeeQuery(get_called_class());
    }
}
