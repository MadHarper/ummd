<?php

use yii\db\Migration;

/**
 * Handles adding master_iogv_id to table `mission`.
 */
class m180122_064503_add_master_iogv_id_column_to_mission_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('mission', 'master_iogv_id', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('mission', 'master_iogv_id');
    }
}
