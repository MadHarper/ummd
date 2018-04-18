<?php

use yii\db\Migration;

/**
 * Handles adding report_overdue to table `mission`.
 */
class m180418_100622_add_report_overdue_column_to_mission_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mission', 'report_overdue', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('mission', 'report_overdue');
    }
}
