<?php

use yii\db\Migration;

/**
 * Handles adding city_id to table `mission`.
 */
class m180330_081656_add_city_id_column_to_mission_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mission', 'city_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('mission', 'city_id');
    }
}
