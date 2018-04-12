<?php

use yii\db\Migration;

/**
 * Handles the creation of table `beseda_employee`.
 */
class m180412_121455_create_beseda_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('beseda_employee', [
            'id'                => $this->primaryKey(),
            'beseda_id'         => $this->integer(),
            'employee_id'       => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-bes_emp-beseda_id',
            'beseda_employee',
            'beseda_id',
            'beseda',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-emp_emp-beseda_id',
            'beseda_employee',
            'employee_id',
            'employee',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('beseda_employee');
    }
}
