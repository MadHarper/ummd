<?php

use yii\db\Migration;

/**
 * Handles adding type to table `document`.
 */
class m180111_105617_add_type_column_to_document_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('document', 'type', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('document', 'type');
    }
}
