<?php

use yii\db\Migration;

/**
 * Handles adding created_at to table `mission`.
 */
class m180117_131820_add_created_at_column_to_mission_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('mission', 'created_at', $this->integer());
        $this->addColumn('mission', 'updated_at', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('mission', 'created_at');
        $this->dropColumn('mission', 'updated_at');
    }
}
