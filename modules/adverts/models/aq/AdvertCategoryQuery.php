<?php

namespace app\modules\adverts\models\aq;

use app\modules\core\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[AdvertCategory]].
 *
 * @see AdvertCategory
 */
class AdvertCategoryQuery extends ActiveQuery
{
    /**
     * @return ActiveQuery
     */
    public function active()
    {
        return $this->andWhere('[[status]]=1');
    }

    /**
     * @inheritdoc
     * @return AdvertCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdvertCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
