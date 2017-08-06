<?php

namespace app\modules\core\models\aq;

/**
 * This is the ActiveQuery class for [[\app\modules\core\models\ar\Look]].
 *
 * @see \app\modules\core\models\ar\Look
 */
class LookQuery extends \app\modules\core\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\core\models\ar\Look[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\core\models\ar\Look|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
