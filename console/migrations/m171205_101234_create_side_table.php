<?php

use yii\db\Migration;

/**
 * Handles the creation of table `side`.
 */
class m171205_101234_create_side_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('side_agr', [
            'id' => $this->primaryKey(),
            'desc' => $this->text(),
            'subdivision' => $this->text(),
            'agreement_id' => $this->integer()->notNull(),
            'org_id' => $this->integer()->notNull(),
            'employee_id' => $this->integer()
        ]);

        $this->addForeignKey(
            'fk-sideagr_agr_id',
            'side_agr',
            'agreement_id',
            'agreement',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-sideagr_org_id',
            'side_agr',
            'org_id',
            'organization',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-sideagr_emp_id',
            'side_agr',
            'employee_id',
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
        $this->dropTable('side');
    }
}
