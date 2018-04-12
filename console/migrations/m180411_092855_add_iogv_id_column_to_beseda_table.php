<?php

use yii\db\Migration;

/**
 * Handles adding iogv_id to table `beseda`.
 */
class m180411_092855_add_iogv_id_column_to_beseda_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('beseda', 'iogv_id', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('beseda', 'iogv_id');
    }
}
