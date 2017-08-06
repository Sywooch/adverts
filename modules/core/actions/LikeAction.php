<?php

namespace app\modules\core\actions;

use app\modules\core\base\Action;
use app\modules\core\models\ar\Like;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class LikeAction extends Action
{
    const CHANGE_MINUS = 'minus';
    const CHANGE_NONE = 'none';
    const CHANGE_PLUS = 'plus';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $request = Yii::$app->request;
        $ownerModelName = $this->modelName;
        $ownerId = $request->get('owner_id');

        $mainModel = $this->findModel($ownerId);
        if (!$mainModel) {
            throw new NotFoundHttpException();
        }

        $attributes = [
            'user_id' => Yii::$app->user->id,
            'owner_id' => $ownerId,
            'owner_model_name' => $ownerModelName::shortClassName(),
        ];
        if (!$likeModel = Like::findOne($attributes)) {
            $likeModel = new Like($attributes);
        }

        $oldValue = $likeModel->value;
        $newValue = intval($request->get('value'));
        $like = self::CHANGE_NONE;
        $dislike = self::CHANGE_NONE;

        if (!$likeModel->isNewRecord && $newValue == $oldValue) {
            $likeModel->delete();
            if ($newValue == Like::LIKE_VALUE) {
                $like = self::CHANGE_MINUS;
            } else if ($newValue == Like::DISLIKE_VALUE) {
                $dislike = self::CHANGE_MINUS;
            }
        } else {
            if (!$likeModel->isNewRecord) {
                if ($newValue == Like::LIKE_VALUE) {
                    $like = self::CHANGE_PLUS;
                    $dislike = self::CHANGE_MINUS;
                } else if ($newValue == Like::DISLIKE_VALUE) {
                    $like = self::CHANGE_MINUS;
                    $dislike = self::CHANGE_PLUS;
                }
            } else {
                if ($newValue == Like::LIKE_VALUE) {
                    $like = self::CHANGE_PLUS;
                } else if ($newValue == Like::DISLIKE_VALUE) {
                    $dislike = self::CHANGE_PLUS;
                }
            }
            $likeModel->value = $newValue;
            $likeModel->save();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'like' => $like,
            'dislike' => $dislike,
        ];
    }
}