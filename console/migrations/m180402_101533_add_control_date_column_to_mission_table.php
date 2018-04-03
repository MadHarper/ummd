<?php

use yii\db\Migration;

/**
 * Handles adding control_date to table `mission`.
 */
class m180402_101533_add_control_date_column_to_mission_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mission', 'contol_date', $this->date()->defaultValue(null));
        $this->addColumn('mission', 'with_boss', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('mission', 'contol_date');
    }
}
