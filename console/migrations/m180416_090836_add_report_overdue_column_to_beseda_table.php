<?php

use yii\db\Migration;

/**
 * Handles adding report_overdue to table `beseda`.
 */
class m180416_090836_add_report_overdue_column_to_beseda_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('beseda', 'report_overdue', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('beseda', 'report_overdue');
    }
}
