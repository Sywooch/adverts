<?php

namespace app\modules\core\actions;

use app\modules\core\base\Action;
use app\modules\core\models\ar\Bookmark;
use app\modules\core\models\ar\Like;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class BookmarkToggleAction extends Action
{
    const ACTION_ADD = 'add';
    const ACTION_DELETE = 'delete';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $request = Yii::$app->request;
        $ownerId = $request->get('id');

        if (!$mainModel = $this->findModel($ownerId)) {
            throw new NotFoundHttpException();
        }

        $attributes = [
            'user_id' => Yii::$app->user->id,
            'owner_id' => $ownerId,
            'owner_model_name' => $mainModel::shortClassName(),
        ];
        ;
        if (!$bookmarkModel = Bookmark::findOne($attributes)) {
            $bookmarkModel = new Bookmark($attributes);
            $bookmarkModel->save();
            $action = self::ACTION_ADD;
        } else {
            $bookmarkModel->delete();
            $action = self::ACTION_DELETE;
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'action' => $action
        ];
    }
}