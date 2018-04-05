<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tbl_dynagrid_dtl`.
 */
class m180405_064221_create_tbl_dynagrid_dtl_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tbl_dynagrid_dtl', [
            'id' => $this->string(100)->notNull(),
            'category' => $this->string(10)->notNull(),
            'name' => $this->string(150)->notNull(),
            'data' => $this->text()->defaultValue(NULL),
            'dynagrid_id' => $this->string(100)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tbl_dynagrid_dtl');
    }
}
