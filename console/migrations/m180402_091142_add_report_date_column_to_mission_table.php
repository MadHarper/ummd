<?php

use yii\db\Migration;

/**
 * Handles adding report_date to table `mission`.
 */
class m180402_091142_add_report_date_column_to_mission_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mission', 'report_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('mission', 'report_date');
    }
}
