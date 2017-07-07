<?php

namespace app\modules\users\models\aq;

/**
 * This is the ActiveQuery class for [[\app\modules\users\models\ar\AuthFail]].
 *
 * @see \app\modules\users\models\ar\AuthFail
 */
class AuthFailQuery extends \app\modules\core\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\users\models\ar\AuthFail[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\users\models\ar\AuthFail|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
