<?php

use yii\db\Migration;

/**
 * Handles adding meropriyatie to table `agreement`.
 */
class m180329_105931_add_meropriyatie_column_to_agreement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agreement', 'meropriatie', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('agreement', 'meropriatie');
    }
}
