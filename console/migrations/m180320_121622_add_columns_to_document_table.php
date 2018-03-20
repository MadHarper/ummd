<?php

use yii\db\Migration;

/**
 * Class m180320_121622_add_columns_to_document_table
 */
class m180320_121622_add_columns_to_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('document', 'doc_type_id', $this->integer());
        $this->addColumn('document', 'doc_date', $this->date());
        $this->addColumn('document', 'name', $this->string());
        $this->addColumn('document', 'note', $this->text());

        $this->addForeignKey(
            'fk-docum-doc_type_id',
            'document',
            'doc_type_id',
            'document_type',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('document', 'doc_type_id');
        $this->dropColumn('document', 'doc_date');
        $this->dropColumn('document', 'name');
        $this->dropColumn('document', 'note');

        $this->dropForeignKey(
            'fk-docum-doc_type_id',
            'document'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180320_121622_add_columns_to_document_table cannot be reverted.\n";

        return false;
    }
    */
}
