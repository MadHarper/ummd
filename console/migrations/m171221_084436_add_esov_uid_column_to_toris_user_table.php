<?php

use yii\db\Migration;

/**
 * Handles adding esov_uid to table `toris_user`.
 */
class m171221_084436_add_esov_uid_column_to_toris_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('toris_user', 'esov_uid', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('toris_user', 'esov_uid');
    }
}
