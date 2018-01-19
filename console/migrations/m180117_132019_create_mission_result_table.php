<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mission_result`.
 */
class m180117_132019_create_mission_result_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('mission_result', [
            'id'            => $this->primaryKey(),
            'result'        => $this->text(),
            'mission_id'    => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-mission-res-mission_id',
            'mission_result',
            'mission_id',
            'mission',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('mission_result');
    }
}
