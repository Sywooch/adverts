<?php

namespace app\modules\geography\models\aq;

/**
 * This is the ActiveQuery class for [[Geography]].
 *
 * @see Geography
 */
class GeographyQuery extends \app\modules\core\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Geography[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Geography|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
