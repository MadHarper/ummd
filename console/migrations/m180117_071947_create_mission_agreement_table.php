<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mission_agreement`.
 */
class m180117_071947_create_mission_agreement_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('mission_agreement', [
            'id'            => $this->primaryKey(),
            'mission_id'    => $this->integer(),
            'agreement_id'    => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-mission_agr-mission_id',
            'mission_agreement',
            'mission_id',
            'mission',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-mission_agr-agr_id',
            'mission_agreement',
            'agreement_id',
            'agreement',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('mission_agreement');
    }
}
