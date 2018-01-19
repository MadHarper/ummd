<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mission`.
 */
class m180117_065621_create_mission_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('mission', [
            'id'            => $this->primaryKey(),
            'name'          => $this->text()->notNull(),
            'date_start'    => $this->date(),
            'date_end'      => $this->date(),
            'country_id'    => $this->integer()->notNull(),
            'region_id'     => $this->integer(),
            'city_id'       => $this->integer(),
            'order'         => $this->string()->notNull(),
            'target'        => $this->text(),
            'iogv_id'       => $this->integer(),
            'duty_man_id'   => $this->integer(),
        ]);


        $this->addForeignKey(
            'fk-mission-country_id',
            'mission',
            'country_id',
            'country',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-mission-duty-emp_id',
            'mission',
            'duty_man_id',
            'employee',
            'id',
            'RESTRICT'
        );

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('mission');
    }
}
