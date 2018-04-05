<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tbl_dynagrid`.
 */
class m180405_064201_create_tbl_dynagrid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tbl_dynagrid', [
            'id' => $this->string(100)->notNull(),
            'filter_id' => $this->string(100),
            'sort_id' => $this->string(100),
            'data' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tbl_dynagrid');
    }
}
