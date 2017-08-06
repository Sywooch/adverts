<?php

namespace app\modules\core\models\aq;

/**
 * This is the ActiveQuery class for [[\app\modules\core\models\ar\Comment]].
 *
 * @see \app\modules\core\models\ar\Comment
 */
class CommentQuery extends \app\modules\core\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\core\models\ar\Comment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\core\models\ar\Comment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
