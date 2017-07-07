<?php

namespace app\modules\users\models\aq;

use app\modules\users\models\ar\User;

/**
 * This is the ActiveQuery class for [[\app\modules\users\models\ar\User]].
 *
 * @see \app\modules\users\models\ar\User
 */
class UserQuery extends \app\modules\core\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['status' => User::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     * @return \app\modules\users\models\ar\User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\users\models\ar\User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
