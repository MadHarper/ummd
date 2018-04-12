<?php

use yii\db\Migration;

/**
 * Handles the creation of table `beseda`.
 */
class m180411_064501_create_beseda_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('beseda', [
            'id'                => $this->primaryKey(),
            'theme'             => $this->text()->notNull(),
            'target'            => $this->text(),
            'created_at'        => $this->integer(),
            'updated_at'        => $this->integer(),
            'date_start'        => $this->date(),
            'date_start_time'   => $this->date(),
            'iniciator_id'      => $this->integer()->defaultValue(NULL),
            'report_date'       => $this->date(),
            'control_date'      => $this->date(),
            'status'            => $this->integer(),
            'notes'             => $this->text()

        ]);

        $this->addForeignKey(
            'fk-beseda_org_id',
            'beseda',
            'iniciator_id',
            'organization',
            'id',
            'RESTRICT'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('beseda');
    }
}
