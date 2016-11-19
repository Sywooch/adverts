<?php

use yii\db\Migration;
use app\modules\users\models\User;

class m140809_072112_insert_superadmin_to_user extends Migration
{
    public function up()
    {
        $user = new User();
        $user->superadmin = 1;
        $user->status = User::STATUS_ACTIVE;
        $user->username = 'superadmin';
        $user->password = 'superadmin';
        $user->save(false);
    }

    public function down()
    {
        if ($user = User::findByUsername('superadmin')) {
            $user->delete();
        }
    }
}
