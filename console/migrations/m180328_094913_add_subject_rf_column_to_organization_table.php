<?php

use yii\db\Migration;

/**
 * Handles adding subject_rf to table `organization`.
 */
class m180328_094913_add_subject_rf_column_to_organization_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('organization', 'subject_rf', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('organization', 'subject_rf');
    }
}
