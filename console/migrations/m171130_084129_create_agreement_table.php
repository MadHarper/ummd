<?php

use yii\db\Migration;

/**
 * Handles the creation of table `agreement`.
 */
class m171130_084129_create_agreement_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('agreement', [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->notNull(),
            'name' => $this->text()->notNull(),
            'date_start' => $this->date(),
            'date_end' => $this->date(),
            'iogv_id' => $this->integer(),
            'desc' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('agreement');
    }
}
