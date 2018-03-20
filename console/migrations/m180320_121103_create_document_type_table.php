<?php

use yii\db\Migration;

/**
 * Handles the creation of table `document_type`.
 */
class m180320_121103_create_document_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('document_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'visible' => $this->boolean()->defaultValue(true)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('document_type');
    }
}
