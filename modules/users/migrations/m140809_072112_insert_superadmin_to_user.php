<?php

use yii\db\Migration;
use app\modules\users\models\ar\User;

/**
 * Class m140809_072112_insert_superadmin_to_user
 */
class m140809_072112_insert_superadmin_to_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $user = new User([
            'email' => 'roman444uk@mail.ru',
            'passwordNotEncrypted' => 'september',
            'status' => User::STATUS_ACTIVE,
            'superadmin' => 1
        ]);
        $user->save(false);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        if ($user = User::findByUsername('superadmin')) {
            $user->delete();
        }
    }
}
