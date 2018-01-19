<?php

use yii\db\Migration;

/**
 * Handles the creation of table `region`.
 */
class m180117_082716_create_region_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('region', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('region');
    }
}
