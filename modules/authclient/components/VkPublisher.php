<?php

namespace app\modules\authclient\components;

use app\modules\adverts\helpers\AdvertHelper;
use app\modules\adverts\models\ar\Advert;
use app\modules\authclient\clients\VKontakte;
use app\modules\core\models\ar\File;
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

        if ($model->files) {
            $data = $client->post('photos.getWallUploadServer', [
                'group_id' => VKONTAKTE_GROUP_ID_POSITIVE
            ]);
            $uploadUrl = $data['response']['upload_url'];

            foreach ($model->files as $file) {
                $client->post('photos.saveWallPhoto', [
                    'group_id' => VKONTAKTE_GROUP_ID_POSITIVE,
                    'photo' => $file
                ]);

                $file->save();
            }
        }

        $client->post('wall.post', [
            'owner_id' => VKONTAKTE_GROUP_ID_NEGATIVE,
            'message' => AdvertHelper::getPostContent($model),
            'from_group' => 1,
            'guid' => $model->id,
        ]);
    }

    /**
     * @param File[] $file
     * @param string $uploadUrl
     */
    public function uploadFile($files, $uploadUrl)
    {
        if ($curl = curl_init()) {
            curl_setopt($curl,CURLOPT_URL,$uploadUrl);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $photos_array);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $photos = curl_exec($curl); // Получаем ответ
            $photos = json_decode($photos); // Разбираем JSON
            curl_close($curl);
        }
    }
}