<?php

use yii\db\Migration;

/**
 * Class m180124_123924_add_column_to_organization_table
 */
class m180124_123924_add_column_to_organization_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('organization', 'history', $this->boolean());
        $this->addColumn('organization', 'prev_id', $this->integer());
        $this->addColumn('organization', 'main_id', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('organization', 'history');
        $this->dropColumn('organization', 'prev_id');
        $this->dropColumn('organization', 'main_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180124_123924_add_column_to_organization_table cannot be reverted.\n";

        return false;
    }
    */
}
