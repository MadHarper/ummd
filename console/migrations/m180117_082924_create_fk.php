<?php

use yii\db\Migration;

/**
 * Class m180117_082924_create_fk
 */
class m180117_082924_create_fk extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-mission-region_id',
            'mission',
            'region_id',
            'region',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-mission-city_id',
            'mission',
            'city_id',
            'city',
            'id',
            'RESTRICT'
        );


        //ToDo на таблицу organization
        $this->addForeignKey(
            'fk-mission-iogv-org_id',
            'mission',
            'iogv_id',
            'organization',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-mission-region_id',
            'mission'
        );

        $this->dropForeignKey(
            'fk-mission-city_id',
            'mission'
        );

        $this->dropForeignKey(
            'fk-mission-iogv-org_id',
            'mission'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180117_082924_create_fk cannot be reverted.\n";

        return false;
    }
    */
}
