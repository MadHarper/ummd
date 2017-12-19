<?php

use yii\db\Migration;

/**
 * Handles adding user_id to table `document`.
 */
class m171215_113042_add_user_id_column_to_document_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('document', 'user_id', $this->integer());

        $this->addForeignKey(
            'fk-document_user_id',
            'document',
            'user_id',
            'toris_user',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('document', 'user_id');
    }
}
