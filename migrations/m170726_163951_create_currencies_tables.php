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
            'code'                  => 'CHAR(3) NOT NULL',
            'sign'                  => 'VARCHAR(12) NOT NULL',
            'short_name'            => 'VARCHAR(8) NOT NULL',
        ], $this->tableOptions);

        $this->createTable('currency_rate', [
            'id'                    => 'pk',
            'src_id'                => 'INTEGER(2) NOT NULL',
            'dst_id'                => 'INTEGER(2) NOT NULL',
            'value'                 => 'DECIMAL(8,5)',
        ], $this->tableOptions);

        $this->addForeignKey('FK_currency_rate_REFS_currency_src', 'currency_rate', 'src_id', 'currency', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('FK_currency_rate_REFS_currency_dst', 'currency_rate', 'dst_id', 'currency', 'id', 'NO ACTION', 'NO ACTION');
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
