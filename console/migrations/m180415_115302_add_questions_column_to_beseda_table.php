<?php

use yii\db\Migration;

/**
 * Handles adding questions to table `beseda`.
 */
class m180415_115302_add_questions_column_to_beseda_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('beseda', 'questions', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('beseda', 'questions');
    }
}
