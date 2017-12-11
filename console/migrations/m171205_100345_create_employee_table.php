<?php

use yii\db\Migration;

/**
 * Handles the creation of table `employee`.
 */
class m171205_100345_create_employee_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('employee', [
            'id' => $this->primaryKey(),
            'fio' => $this->string(),
            'position' => $this->string(),
            'active' => $this->boolean()->defaultValue(true),
            'organization_id' => $this->integer()
        ]);

        $this->addForeignKey(
            'fk-employee-organ_id',
            'employee',
            'organization_id',
            'organization',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('employee');
    }
}
