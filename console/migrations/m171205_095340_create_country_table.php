<?php

use yii\db\Migration;

/**
 * Handles the creation of table `country`.
 */
class m171205_095340_create_country_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('country', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('country');
    }
}
