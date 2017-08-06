<?php

use app\modules\core\db\Migration;

class m170726_163951_create_currencies_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('currency', [
            'id'                    => 'pk',
            'name'                  => 'VARCHAR(32) NOT NULL',
            'abbreviation'          => 'CHAR(3) NOT NULL',
            'sign'                  => 'VARCHAR(12) NOT NULL',
        ], $this->tableOptions);

        $this->createTable('currency_course', [
            'id'                    => 'pk',
            'parent_id'             => 'INTEGER(4) NOT NULL',
            'child_id'              => 'INTEGER(4) NOT NULL',
            'value'                 => 'DECIMAL(10,2)',
        ], $this->tableOptions);
        $this->addForeignKey('fk_currency_course_refs_currency_parent', 'currency_course', 'parent_id', 'currency', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_currency_course_refs_currency_child', 'currency_course', 'child_id', 'currency', 'id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('currency_course');
        $this->dropTable('currency');
    }
}
