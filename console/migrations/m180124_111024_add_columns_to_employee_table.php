<?php

use yii\db\Migration;

/**
 * Class m180124_111024_add_columns_to_employee_table
 */
class m180124_111024_add_columns_to_employee_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('employee', 'created_at', $this->integer());
        $this->addColumn('employee', 'updated_at', $this->integer());
        $this->addColumn('employee', 'history', $this->boolean());
        $this->addColumn('employee', 'prev_id', $this->integer());
        $this->addColumn('employee', 'main_id', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('employee', 'created_at');
        $this->dropColumn('employee', 'updated_at');
        $this->dropColumn('employee', 'history');
        $this->dropColumn('employee', 'prev_id');
        $this->dropColumn('employee', 'main_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180124_111024_add_columns_to_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
