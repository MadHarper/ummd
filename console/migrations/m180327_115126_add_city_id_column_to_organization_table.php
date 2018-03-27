<?php

use yii\db\Migration;

/**
 * Handles adding city_id to table `organization`.
 */
class m180327_115126_add_city_id_column_to_organization_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('organization', 'city_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('organization', 'city_id');
    }
}
