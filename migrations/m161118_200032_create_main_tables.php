<?php

use yii\db\Migration;

use app\modules\adverts\models\ar\Advert;

/**
 * Class m161118_200032_create_main_tables
 */
class m161118_200032_create_main_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('geography_type', [
            'id'                    => 'pk',
            'name'                  => 'VARCHAR(32)'
        ], $tableOptions);

        $this->createTable('geography', [
            'id'                    => 'pk',
            'type_id'               => 'INT(2)',
            'name'                  => 'VARCHAR(64) not null',
            'active'                => 'TINYINT(1) DEFAULT 1',
            'parent_id'             => 'INT(11)',
        ], $tableOptions);
        $this->addForeignKey('fk_geography_refs_geography_type', 'geography', 'type_id', 'geography_type', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_geography_refs_geography', 'geography', 'parent_id', 'geography', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_category', [
            'id'                    => 'pk',
            'name'                  => 'VARCHAR(32) NOT NULL',
            'parent_id'             => 'INT(4) DEFAULT NULL'
        ], $tableOptions);
        $this->addForeignKey('fk_advert_category_refs_advert_category', 'advert_category', 'parent_id', 'advert_category', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('currency', [
            'id'                    => 'pk',
            'name'                  => 'VARCHAR(32) NOT NULL',
            'abbreviaton'           => 'CHAR(3) NOT NULL',
            'sign'                  => 'VARCHAR(12) NOT NULL',
        ], $tableOptions);

        $this->createTable('currency_course', [
            'id'                    => 'pk',
            'parent_id'             => 'INTEGER(4) NOT NULL',
            'child_id'              => 'INTEGER(4) NOT NULL',
            'value'                 => 'DECIMAL(10,2)',
        ], $tableOptions);
        $this->addForeignKey('fk_currency_course_refs_currency', 'advert_category', 'parent_id', 'advert_category', 'id', 'NO ACTION', 'NO ACTION');

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
        ], $tableOptions);
        $this->addForeignKey('fk_advert_refs_user', 'advert', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_refs_advert_category', 'advert', 'category_id', 'advert_category', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_refs_geography', 'advert', 'geography_id', 'geography', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_refs_currency', 'advert', 'currency_id', 'currency', 'id', 'NO ACTION', 'NO ACTION');

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
        ], $tableOptions);
        $this->addForeignKey('fk_advert_templet_refs_user', 'advert_templet', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_templet_refs_advert_category', 'advert_templet', 'category_id', 'advert_category', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_templet_refs_geography', 'advert_templet', 'geography_id', 'geography', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_file', [
            'id'                    => 'pk',
            'advert_id'             => 'INT(11) NOT NULL',
            'url'                   => 'INT(11) NOT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_advert_file_refs_advert', 'advert_file', 'advert_id', 'advert', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_comment', [
            'id'                    => 'pk',
            'advert_id'             => 'INT(11) NOT NULL',
            'user_id'               => 'INT(11) NOT NULL',
            'text'                  => 'INT(11) NOT NULL',
            'created_at'            => 'timestamp',
            'updated_at'            => 'timestamp',
        ], $tableOptions);
        $this->addForeignKey('fk_advert_comment_refs_advert', 'advert_comment', 'advert_id', 'advert', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_comment_refs_user', 'advert_comment', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_view', [
            'advert_id'             => 'INT(11) NOT NULL',
            'user_id'               => 'INT(11) NOT NULL',
            'views'                 => 'INT(4)',
        ], $tableOptions);
        $this->addForeignKey('fk_advert_view_refs_advert', 'advert_view', 'advert_id', 'advert', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_view_refs_user', 'advert_view', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_like', [
            'advert_id'             => 'INT(11) NOT NULL',
            'user_id'               => 'INT(11) NOT NULL',
            'like'                  => 'TINYINT(1) DEFAULT 0'
        ], $tableOptions);
        $this->addForeignKey('fk_advert_like_refs_advert', 'advert_like', 'advert_id', 'advert', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_like_refs_user', 'advert_like', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_bookmark', [
            'advert_id'             => 'INT(11) NOT NULL',
            'user_id'               => 'INT(11) NOT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_advert_bookmark_refs_advert', 'advert_bookmark', 'advert_id', 'advert', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_bookmark_refs_user', 'advert_bookmark', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {

    }
}
