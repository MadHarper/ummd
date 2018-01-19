<?php

use yii\db\Migration;

/**
 * Handles adding visible to table `employee`.
 */
class m180119_091253_add_visible_column_to_employee_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('employee', 'visible', $this->boolean()->defaultValue(true));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('employee', 'visible');
    }
}
