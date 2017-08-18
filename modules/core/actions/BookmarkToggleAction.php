<?php

namespace app\modules\core\actions;

use app\modules\core\base\Action;
use app\modules\core\components\BookmarksManager;
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
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $ownerId = $request->get('id');
        if (!$mainModel = $this->findModel($ownerId)) {
            throw new NotFoundHttpException();
        }

        /** @var BookmarksManager $bookmarksManager */
        $bookmarksManager = Yii::$app->bookmarksManager;
        $ownerModelName = $mainModel::shortClassName();
        if ($bookmarksManager->has($ownerModelName, $ownerId)) {
            $bookmarksManager->delete($ownerModelName, $ownerId);
            $action = self::ACTION_DELETE;
        } else {
            $bookmarksManager->add($ownerModelName, $ownerId);
            $action = self::ACTION_ADD;
        }

        return [
            'success' => true,
            'action' => $action
        ];
    }
}