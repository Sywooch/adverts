<?php

namespace app\modules\users\models\search;

use app\modules\core\data\ActiveDataProvider;
use Yii;
use yii\base\Model;

use app\modules\users\models\ar\User;

class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'superadmin', 'status', 'created_at', 'updated_at', 'email_confirmed'], 'integer'],
            [['registration_ip', 'email'], 'string'],
            [['is_from_service'], 'safe']
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params = [])
    {
        $query = self::find();

        $query->with(['profile.userAuthClient']);

        if (!Yii::$app->user->isSuperadmin) {
            $query->where(['superadmin' => 0]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                //'pageSize' => \roman444uk\yii\widgets\WidgetPageSize::getPageSize(),
                //'defaultPageLast' => true
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'superadmin' => $this->superadmin,
            'status' => $this->status,
            'registration_ip' => $this->registration_ip,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'email_confirmed' => $this->email_confirmed,
            'is_from_service' => $this->is_from_service,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}