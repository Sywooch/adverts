<?php

namespace app\modules\adverts\models\aq;

use app\modules\core\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Advert]].
 *
 * @see Advert
 */
class AdvertQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere('[[status]]=1');
    }

    /**
     * @inheritdoc
     * @return Advert[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Advert|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return $this
     */
    public function published()
    {
        return $this;
    }

    /**
     * @return $this
     */
    public function bookmarked()
    {
        return $this;
    }
}
