<?php

use yii\db\Migration;

/**
 * Handles the creation of table `calendar`.
 */
class m180402_130423_create_calendar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('calendar', [
            'id'            => $this->primaryKey(),
            'day_date'      => $this->date(),
            'is_working'    => $this->boolean()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('calendar');
    }
}
