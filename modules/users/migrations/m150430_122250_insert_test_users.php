<?php

use yii\db\Migration;

use app\modules\users\models\User;

class m150430_122250_insert_test_users extends Migration
{
    public function up()
    {
        /*if (defined('YII_ENV') && YII_ENV == 'dev') {
            for ($i = 1; $i < 51; $i++) {
                $user = new User();
                $user->superadmin = 0;
                $user->status = User::STATUS_ACTIVE;
                $user->username = $this->getUserName($i);
                $user->password = "pass$i";
                $user->email = "user$i@mail.com";
                if ($user->save(false)) {
                    echo "User \"{$user->username}\" created..\r\n";
                }
            }
        }*/
    }

    public function down()
    {
        /*if (defined('YII_ENV') && YII_ENV == 'dev') {
            for ($i = 1; $i < 51; $i++) {
                if ($user = User::findByUsername($this->getUserName($i))) {
                    $user->delete();
                }
            }
        }*/
    }
    
    protected function getUserName($i)
    {
        return "User №$i";
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}