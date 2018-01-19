<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mission_employee`.
 */
class m180117_070754_create_mission_employee_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('mission_employee', [
            'id'                => $this->primaryKey(),
            'mission_id'        => $this->integer(),
            'employee_id'       => $this->integer(),
            'role'              => $this->smallInteger()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-mis_emp-mission_id',
            'mission_employee',
            'mission_id',
            'mission',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-mis_emp-employee_id',
            'mission_employee',
            'employee_id',
            'employee',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('mission_employee');
    }
}
