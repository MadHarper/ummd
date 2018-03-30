<?php

use yii\db\Migration;

/**
 * Handles adding notes to table `mission`.
 */
class m180330_124026_add_notes_column_to_mission_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mission', 'notes', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('mission', 'notes');
    }
}
