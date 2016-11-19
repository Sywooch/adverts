<?php

use yii\db\Migration;

use app\modules\adverts\models\Advert;

class m161118_200032_create_main_tables extends Migration
{
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
            'name'                  => 'varchar(64) not null',
            'active'                => 'TINYINT(1) DEFAULT 1',
            'parent_id'             => 'INT(11)',
        ], $tableOptions);
        $this->addForeignKey('fk_geography_geography_type', 'geography', 'type_id', 'geography_type', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_geography_geography', 'geography', 'parent_id', 'geography', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_category', [
            'id'                    => 'pk',
            'name'                  => 'VARCHAR(32) NOT NULL',
            'parent_id'             => 'INT(4) NOT NULL'
        ], $tableOptions);
        $this->addForeignKey('fk_advert_category_advert_category', 'advert_category', 'parent_id', 'advert_category', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert', [
            'id'                    => 'pk',
            'user_id'               => 'INT(11) NOT NULL',
            'category_id'           => 'INT(3)',
            'geography_id'          => 'INT(11)',
            'content'               => 'TEXT DEFAULT NULL',
            'status'                => 'ENUM("'.implode('","', Advert::getStatusList()).'") DEFAULT "'.Advert::STATUS_NEW.'"',
            'is_templet'            => 'tinyint(1) DEFAULT 0',
            'is_foreign'            => 'TINYINT(1) DEFAULT 0',
            'published'             => 'TINYINT(1) DEFAULT 0',
            'expiry_at'             => 'timestamp',
            'created_at'            => 'timestamp',
            'updated_at'            => 'timestamp',
        ], $tableOptions);
        $this->addForeignKey('fk_advert_user', 'advert', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_advert_category', 'advert', 'category_id', 'advert_category', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_geography', 'advert', 'geography_id', 'geography', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_file', [
            'id'                    => 'pk',
            'advert_id'             => 'INT(11) NOT NULL',
            'url'                   => 'INT(11) NOT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_advert_file_advert', 'advert_file', 'advert_id', 'advert', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_comment', [
            'id'                    => 'pk',
            'advert_id'             => 'INT(11) NOT NULL',
            'user_id'               => 'INT(11) NOT NULL',
            'text'                  => 'INT(11) NOT NULL',
            'created_at'            => 'timestamp',
            'updated_at'            => 'timestamp',
        ], $tableOptions);
        $this->addForeignKey('fk_advert_comment_advert', 'advert_comment', 'advert_id', 'advert', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_comment_user', 'advert_comment', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_view', [
            'advert_id'             => 'INT(11) NOT NULL',
            'user_id'               => 'INT(11) NOT NULL',
            'views'                 => 'INT(4)',
        ], $tableOptions);
        $this->addForeignKey('fk_advert_view_advert', 'advert_view', 'advert_id', 'advert', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_view_user', 'advert_view', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_like', [
            'advert_id'             => 'INT(11) NOT NULL',
            'user_id'               => 'INT(11) NOT NULL',
            'like'                  => 'TINYINT(1) DEFAULT 0'
        ], $tableOptions);
        $this->addForeignKey('fk_advert_like_advert', 'advert_like', 'advert_id', 'advert', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_like_user', 'advert_like', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('advert_bookmark', [
            'advert_id'             => 'INT(11) NOT NULL',
            'user_id'               => 'INT(11) NOT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_advert_bookmark_advert', 'advert_bookmark', 'advert_id', 'advert', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_advert_bookmark_user', 'advert_bookmark', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('advert_bookmark');
        $this->dropTable('advert_like');
        $this->dropTable('advert_view');
        $this->dropTable('advert_file');
        $this->dropTable('advert');
        $this->dropTable('category');
        $this->dropTable('geography');
        $this->dropTable('geography_type');
    }
}
