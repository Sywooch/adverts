<?php

namespace app\modules\authclient\widgets;

use yii\helpers\Html;

class AuthChoice extends \yii\authclient\widgets\AuthChoice
{
    /**
     * @inheritdoc
     */
    protected function renderMainContent()
    {
        $items = [];
        foreach ($this->getClients() as $externalService) {
            $items[] = $this->clientLink($externalService);
        }
        return Html::tag('div', implode('', $items), ['class' => 'auth-clients']);
    }
}