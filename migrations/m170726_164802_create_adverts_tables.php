<?php

use app\modules\adverts\models\ar\Advert;
use app\modules\core\db\Migration;

class m170726_164802_create_adverts_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('advert_category', [
            'id'                    => 'pk',
            'name'                  => 'VARCHAR(32) NOT NULL',
            'parent_id'             => 'INT(4) DEFAULT NULL'
        ], $this->tableOptions);
        $this->addForeignKey('fk_advert_category_refs_advert_category', 'advert_category', 'parent_id', 'advert_category', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert', [
            'id'                    => 'pk',
            'user_id'               => 'INT(11) NOT NULL',
            'category_id'           => 'INT(3)',
            'geography_id'          => 'INT(11)',
            'currency_id'           => 'INT(11)',
            'content'               => 'TEXT DEFAULT NULL',
            'status'                => 'ENUM("'.implode('","', Advert::getAttributeLabels('status')).'") DEFAULT "'.Advert::STATUS_NEW.'"',
            'is_foreign'            => 'TINYINT(1) DEFAULT 0',
            'published'             => 'TINYINT(1) DEFAULT 0',
            'expiry_at'             => 'TIMESTAMP DEFAULT NULL',
            'created_at'            => 'TIMESTAMP DEFAULT NOW()',
            'updated_at'            => 'TIMESTAMP DEFAULT NOW()',
            'min_price'             => 'DECIMAL(10,2)',
            'max_price'             => 'DECIMAL(10,2)',
        ], $this->tableOptions);
        $this->addForeignKey('fk_advert_refs_user', 'advert', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_refs_advert_category', 'advert', 'category_id', 'advert_category', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_refs_geography', 'advert', 'geography_id', 'geography', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_refs_currency', 'advert', 'currency_id', 'currency', 'id', 'NO ACTION', 'NO ACTION');
        //$this->createIndex();

        $this->createTable('advert_templet', [
            'id'                    => 'pk',
            'user_id'               => 'INT(11) NOT NULL',
            'category_id'           => 'INT(3)',
            'geography_id'          => 'INT(11)',
            'currency_id'           => 'INT(11)',
            'content'               => 'TEXT DEFAULT NULL',
            'expiry_at'             => 'timestamp',
            'updated_at'            => 'timestamp',
            'min_price'             => 'DECIMAL(10,2)',
            'max_price'             => 'DECIMAL(10,2)',
        ], $this->tableOptions);
        $this->addForeignKey('fk_advert_templet_refs_user', 'advert_templet', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_templet_refs_advert_category', 'advert_templet', 'category_id', 'advert_category', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_templet_refs_geography', 'advert_templet', 'geography_id', 'geography', 'id', 'NO ACTION', 'NO ACTION');
    }

    /**
    * @inheritdoc
    */
    public function down()
    {
        $this->dropTable('advert_templet');
        $this->dropTable('advert');
        $this->dropTable('advert_category');
    }
}
