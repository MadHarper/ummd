<?php

use yii\db\Migration;

/**
 * Handles adding boss to table `mission_employee`.
 */
class m180402_093041_add_boss_column_to_mission_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mission_employee', 'boss', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('mission_employee', 'boss');
    }
}
