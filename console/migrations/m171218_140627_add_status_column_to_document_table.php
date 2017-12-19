<?php

use yii\db\Migration;

/**
 * Handles adding status to table `document`.
 */
class m171218_140627_add_status_column_to_document_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('document', 'status', $this->integer()->defaultValue(1));
        $this->addColumn('document', 'iogv_id', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('document', 'status');
        $this->dropColumn('document', 'iogv_id');
    }
}
