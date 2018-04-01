<?php

use yii\db\Migration;

/**
 * Handles adding status to table `mission`.
 */
class m180330_130441_add_status_column_to_mission_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mission', 'status', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('mission', 'status');
    }
}
