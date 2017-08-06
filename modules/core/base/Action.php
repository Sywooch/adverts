<?php

namespace app\modules\core\base;

use app\modules\core\db\ActiveRecord;

class Action extends \yii\base\Action
{
    /**
     * @var ActiveRecord $model AR model
     */
    public $modelName;

    /**
     * @var string
     */
    public $modelAttribute;

    /**
     * @var callable
     */
    public $findModelCallback;

    /**
     * @param integer $id
     * @return ActiveRecord
     */
    protected function findModel($id)
    {
        if ($this->findModelCallback) {
            $model = call_user_func($this->findModelCallback, $id);
        } else {
            $model = ($this->modelName)::findOne(intval($id));
        }

        if ($model == null) {
            $model = new $this->modelName;
        }

        return $model;
    }
}