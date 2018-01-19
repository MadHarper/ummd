<?php

use yii\db\Migration;

/**
 * Handles the creation of table `city`.
 */
class m180117_082622_create_city_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('city', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('city');
    }
}
