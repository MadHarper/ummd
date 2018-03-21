<?php

use yii\db\Migration;

/**
 * Handles adding state to table `agreement`.
 */
class m180321_114032_add_state_column_to_agreement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agreement', 'state', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('agreement', 'state');
    }
}
