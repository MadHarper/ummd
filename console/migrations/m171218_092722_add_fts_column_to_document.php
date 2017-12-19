<?php

use yii\db\Migration;

/**
 * Class m171218_092722_add_fts_column_to_document
 */
class m171218_092722_add_fts_column_to_document extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        /*
         * PREPARE SEARCH CONFIGURATION
         *----------------------------
         */
        $this->getDb()->createCommand(
            '
           CREATE TEXT SEARCH DICTIONARY ispell_ru (
           template  =   ispell,
           dictfile  =   ru,
           afffile   =   ru,
           stopwords =   russian
           );
           '
        )->execute();

        $this->getDb()->createCommand(
            '
           CREATE TEXT SEARCH DICTIONARY ispell_en (
           template  = ispell,
           dictfile  = en,
           afffile   = en,
           stopwords = english
           );
           '
        )->execute();

        $this->getDb()->createCommand('CREATE TEXT SEARCH CONFIGURATION ru ( COPY = russian );')->execute();


        $this->getDb()->createCommand(
            'ALTER TEXT SEARCH CONFIGURATION ru
           ALTER MAPPING
           FOR word, hword, hword_part
           WITH ispell_ru, russian_stem;
           '
        )->execute();

        $this->getDb()->createCommand(
            'ALTER TEXT SEARCH CONFIGURATION ru
           ALTER MAPPING
           FOR asciiword, asciihword, hword_asciipart
           WITH ispell_en, english_stem;'
        )->execute();

        $this->getDb()->createCommand('SET default_text_search_config = \'ru\';')->execute();

        /** ADD tsvector column **/
        $this->getDb()->createCommand(
            '
           ALTER TABLE {{%document}} ADD COLUMN fts tsvector;
        '
        )->execute();


        $this->getDb()->createCommand(
            '
           UPDATE {{%document}} SET fts=
            setweight( coalesce( to_tsvector(\'ru\', [[origin_name]]),\'\'),\'A\') || \' \' ||
            setweight( coalesce( to_tsvector(\'ru\', [[content]]),\'\'),\'B\') || \' \';
        '
        )->execute();


        $this->getDb()->createCommand('create index fts_index on {{%document}} using gin (fts);')->execute();

        /**
         * ---   ADD AUTO FILL fts TRIGGER ON INSERT NEW RECORD
         * (in my case 'on update' trigger not neccessary)
         **/

        $this->getDb()->createCommand(
            '
            CREATE FUNCTION fts_vector_update() RETURNS TRIGGER AS $$
            BEGIN
               NEW.fts=setweight( coalesce( to_tsvector(\'ru\', NEW.origin_name),\'\'),\'A\') || \' \' ||
                        setweight( coalesce( to_tsvector(\'ru\', NEW.content),\'\'),\'B\') || \' \';
                        RETURN NEW;
            END;
            $$ LANGUAGE \'plpgsql\';
        '
        )->execute();

        /*
        $this->getDb()->createCommand(
            '
            CREATE TRIGGER tovar_fts_update BEFORE INSERT ON {{%document}}
            FOR EACH ROW EXECUTE PROCEDURE fts_vector_update();
        '
        )->execute();
        */

        // Мой вариант
        $this->getDb()->createCommand(
            '
            CREATE TRIGGER tovar_fts_update BEFORE UPDATE ON {{%document}}
            FOR EACH ROW EXECUTE PROCEDURE fts_vector_update();
        '
        )->execute();

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('fts_index', '{{%document}}');
        $this->dropColumn('{{%document}}', 'fts');
        $this->getDb()->createCommand('DROP TRIGGER tovar_fts_update ON {{%document}}')->execute();
        $this->getDb()->createCommand('DROP FUNCTION IF EXISTS fts_vector_update()')->execute();
    }


}
