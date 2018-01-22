<?php

use yii\db\Migration;

/**
 * Handles adding visible to table `mission`.
 */
class m180122_073219_add_visible_column_to_mission_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('mission', 'visible', $this->boolean()->defaultValue(true));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('mission', 'visible');
    }
}
