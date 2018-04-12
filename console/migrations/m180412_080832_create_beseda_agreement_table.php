<?php

use yii\db\Migration;

/**
 * Handles the creation of table `beseda_agreement`.
 */
class m180412_080832_create_beseda_agreement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('beseda_agreement', [
            'id'                => $this->primaryKey(),
            'beseda_id'         => $this->integer(),
            'agreement_id'      => $this->integer(),
        ]);


        $this->addForeignKey(
            'fk-beseda_agr-bes_id',
            'beseda_agreement',
            'beseda_id',
            'beseda',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-agreement_agr-bes_id',
            'beseda_agreement',
            'agreement_id',
            'agreement',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('beseda_agreement');
    }
}
