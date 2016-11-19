<?php

use yii\db\Schema;
use yii\db\Migration;

class m140608_173539_create_user_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('user', [
            'id'                 => 'pk',
            'username'           => 'VARCHAR(32) NOT NULL',
            'email'              =>' VARCHAR(128)',
            'email_confirmed'    => 'TINYINT(1) NOT NULL DEFAULT 0',
            'auth_key'           => 'VARCHAR(32) NOT NUlL',
            'password'           => 'VARCHAR(128)',
            'confirmation_token' => 'VARCHAR(128)',
            'status'             => 'INT NOT NULL DEFAULT 1',
            'superadmin'         => 'TINYINT(1) DEFAULT 0',
            'created_at'         => 'TIMESTAMP',
            'updated_at'         => 'TIMESTAMP',
            'registration_ip'    => 'VARCHAR(15)',
        ], $tableOptions);

        $this->createTable('service_user', [
            'service_user_id'   => 'VARCHAR(32)',
            'service_name'      => 'VARCHAR(32)',
            'user_id'           => 'INT(11) NOT NULL',
            'state'             => 'VARCHAR(32)',
            'access_token'      => 'VARCHAR(512)',
            'service_status'    => 'VARCHAR(32)',
            'username'          => 'VARCHAR(128)',
            'avatar_url'        => 'VARCHAR(255)',
            'profile'           => 'TEXT',
            'PRIMARY KEY (service_user_id, service_name)'
        ], $tableOptions);
        $this->addForeignKey('fk_service_user_user', 'service_user', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('user_profile', [
            'id'                 => 'pk',
            'user_id'            => 'INT(11) NOT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_user_profile_user', 'user_profile', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('user_visit_log', [
            'id'            => 'pk',
            'user_id'       => 'INT(11) NOT NULL',
            'token'         => 'VARCHAR(128) NOT NULL',
            'ip'            => 'VARCHAR(15) NOT NULL',
            'language'      => 'CHAR(2) NOT NULL',
            'user_agent'    => 'VARCHAR(255)',
            'browser'       => 'VARCHAR(30)',
            'os'            => 'VARCHAR(20)',
            'visit_time'    => 'TIMESTAMP',
        ], $tableOptions);
        $this->addForeignKey('fk_user_visit_log_user', 'user_visit_log', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('user_visit_log');
        $this->dropTable('user_profile');
        $this->dropTable('service_user');
        $this->dropTable('user');
    }
}
