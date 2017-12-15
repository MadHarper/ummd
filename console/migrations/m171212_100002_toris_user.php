<?php

use yii\db\Migration;

/**
 * Class m171212_100002_toris_user
 */
class m171212_100002_toris_user extends Migration
{
    public function safeUp()
    {
        $this->createTable('toris_user', [
            'id'        => $this->primaryKey(),
            'bx_id'     => $this->integer()->notNull(),
            'iogv_id'   => $this->string()->notNull(),
            'fio'       => $this->string()->null(),
            'aistoken'  => $this->text()->notNull(),
            'created'   => $this->dateTime(),
            'updated'   => $this->dateTime()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('toris_user');
    }
}
