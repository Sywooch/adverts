<?php

namespace app\modules\core\base;

use app\modules\core\db\ActiveRecord;
use app\modules\core\web\Controller;

class Action extends \yii\base\Action
{
    /**
     * @var Controller the controller that owns this action
     */
    public $controller;

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
            $modelName = $this->modelName;
            $model = $modelName::findOne(intval($id));
        }

        if ($model == null) {
            $model = new $this->modelName;
        }

        return $model;
    }
}