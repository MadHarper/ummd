<?php

use yii\db\Migration;

/**
 * Handles adding iogv to table `organization`.
 */
class m180117_142625_add_iogv_column_to_organization_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('organization', 'iogv', $this->boolean()->defaultValue(false));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('organization', 'iogv');
    }
}
