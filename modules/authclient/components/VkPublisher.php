<?php

namespace app\modules\authclient\components;

use app\modules\adverts\helpers\AdvertHelper;
use app\modules\adverts\models\ar\Advert;
use app\modules\authclient\clients\VKontakte;
use yii\base\Component;
use Yii;

class VkPublisher extends Component
{
    /**
     * @param Advert $model
     */
    public function publishAdvert($model)
    {
        $collection = Yii::$app->get('authClientCollection');
        if (!$collection->hasClient('vkontakte')) {
            throw new NotFoundHttpException("Unknown auth client 'vkontakte'");
        }

        /** @var VKontakte $client */
        $client = $collection->getClient('vkontakte');
        $client->setAccessToken([
            'token' => VKONTAKTE_ACCESS_TOKEN
        ]);
        $client->post('wall.post', [
            'owner_id' => VKONTAKTE_GROUP_ID,
            'message' => AdvertHelper::getPostContent($model),
            'from_group' => 1,
            'guid' => $model->id,
        ]);
    }

    public function publishPhotos($models)
    {

    }
}