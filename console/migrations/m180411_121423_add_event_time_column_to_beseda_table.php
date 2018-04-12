<?php

use yii\db\Migration;

/**
 * Handles adding event_time to table `beseda`.
 */
class m180411_121423_add_event_time_column_to_beseda_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('beseda', 'event_time', $this->time());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('beseda', 'event_time');
    }
}
