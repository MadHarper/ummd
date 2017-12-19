<?php

use yii\db\Migration;

/**
 * Handles the creation of table `document`.
 */
class m171215_104137_create_document_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('document', [
            'id' => $this->primaryKey(),
            'model' => $this->string()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'content' => $this->text(),
            'description' => $this->text(),
            'origin_name' => $this->string(),
            'sea_name' => $this->string(),
            'link' => $this->string(),
            'visible' => $this->boolean()->defaultValue(true),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('document');
    }
}
