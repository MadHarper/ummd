<?php

use yii\db\Migration;

/**
 * Handles the creation of table `organization`.
 */
class m171205_095533_create_organization_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('organization', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'contact' => $this->text(),
            'country_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-organizat-country_id',
            'organization',
            'country_id',
            'country',
            'id',
            'RESTRICT'
        );

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('organization');
    }
}
