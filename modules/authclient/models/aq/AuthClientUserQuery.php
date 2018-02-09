<?php

namespace app\modules\authclient\models\aq;

/**
 * This is the ActiveQuery class for [[\app\modules\authclient\models\ar\AuthClientUser]].
 *
 * @see \app\modules\authclient\models\ar\UserAuthClient
 */
class AuthClientUserQuery extends \app\modules\core\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\authclient\models\ar\UserAuthClient[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\authclient\models\ar\UserAuthClient|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
