<?php

use yii\db\Migration;

/**
 * Handles adding address to table `beseda`.
 */
class m180411_134802_add_address_column_to_beseda_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('beseda', 'address', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('beseda', 'address');
    }
}
