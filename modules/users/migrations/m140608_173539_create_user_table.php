<?php

use app\modules\users\models\ar\AuthFail;
use app\modules\users\models\ar\EmailConfirmToken;
use app\modules\users\models\ar\Profile;
use yii\db\Migration;

/**
 * Class m140608_173539_create_user_table
 */
class m140608_173539_create_user_table extends Migration
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

        $this->createTable('user', [
            'id'                 => 'pk',
            'email'              => 'VARCHAR(128) NOT NULL',
            'auth_key'           => 'VARCHAR(32) NOT NUlL',
            'password'           => 'VARCHAR(128)',
            'status'             => 'INT NOT NULL DEFAULT 1',
            'superadmin'         => 'TINYINT(1) DEFAULT 0',
            'created_at'         => 'TIMESTAMP',
            'updated_at'         => 'TIMESTAMP',
            'lastvisit_at'       => 'TIMESTAMP',
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
        $this->addForeignKey('FK_service_user_REFS_user', 'service_user', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $preferableConnectionTypeLabels = array_keys(Profile::getAttributeLabels('preferable_connection_type'));
        $this->createTable('user_profile', [
            'id'                         => 'pk',
            'user_id'                    => 'INT(11) NOT NULL',
            'name'                       => 'VARCHAR(32)',
            'surname'                    => 'VARCHAR(32)',
            'patronymic'                 => 'VARCHAR(32)',
            'skype'                      => 'VARCHAR(32)',
            'isq'                        => 'VARCHAR(32)',
            'page_vk'                    => 'VARCHAR(64)',
            'page_ok'                    => 'VARCHAR(64)',
            'page_fb'                    => 'VARCHAR(64)',
            'phone_1'                    => 'VARCHAR(32)',
            'phone_2'                    => 'VARCHAR(32)',
            'phone_3'                    => 'VARCHAR(32)',
            'preferable_connection_type' => 'ENUM("'.implode('","', $preferableConnectionTypeLabels).'") DEFAULT "'.Profile::CONNECTION_TYPE_PHONE.'"',
        ], $tableOptions);
        $this->addForeignKey('FK_user_profile_FK_user', 'user_profile', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('user_settings', [
            'id'                 => 'pk',
            'user_id'            => 'INT(11) NOT NULL',
        ], $tableOptions);
        $this->addForeignKey('FK_user_settings_FK_user', 'user_settings', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $subjectLabels = array_keys(EmailConfirmToken::getAttributeLabels('subject'));
        $this->createTable('user_email_confirm_token', [
            'id'            => 'pk',
            'user_id'       => 'INT(11) NOT NULL',
            'token'         => 'VARCHAR(128) NOT NULL',
            'email'         => 'VARCHAR(128) NOT NULL',
            'expiry_at'     => 'TIMESTAMP',
            'action'        => 'ENUM("'.implode('","', $subjectLabels).'") DEFAULT "'.EmailConfirmToken::ACTION_REGISTRATION.'"',
        ], $tableOptions);
        $this->addForeignKey('FK_user_email_confirm_token_FK_user', 'user_email_confirm_token', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('user_password_restore_token', [
            'id'            => 'pk',
            'user_id'       => 'INT(11) NOT NULL',
            'token'         => 'VARCHAR(128) NOT NULL',
            'expiry_at'     => 'TIMESTAMP',
        ], $tableOptions);
        $this->addForeignKey('FK_user_password_restore_token_FK_user', 'user_password_restore_token', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('user_auth_log', [
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
        $this->addForeignKey('FK_user_auth_log_FK_user', 'user_auth_log', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $actionLabels = '"' . implode('","', array_keys(AuthFail::getAttributeLabels('action'))) . '"';
        $this->createTable('auth_fail', [
            'id'                 => 'pk',
            'ip'                 => 'CHAR(15)',
            'email'              => 'VARCHAR(128) NOT NULL',
            'action'             => "ENUM({$actionLabels}) DEFAULT 'login'",
            'created_at'         => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_auth_log');
        $this->dropTable('user_profile');
        $this->dropTable('service_user');
        $this->dropTable('user');
    }
}
