<?php

namespace app\modules\adverts\models\ar;

use app\modules\adverts\AdvertsModule;
use app\modules\adverts\models\aq\AdvertQuery;
use app\modules\core\behaviors\TimestampBehavior;
use app\modules\currencies\models\ar\Currency;
use app\modules\users\models\ar\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $geography_id
 * @property integer $currency_id
 * @property string $content
 * @property string $status
 * @property string $is_foreign
 * @property string $published
 * @property string $expiry_at
 * @property string $created_at
 * @property string $updated_at
 * @property float $min_price
 * @property float $max_price
 */
class Advert extends \app\modules\core\db\ActiveRecord
{
    const SCENARIO_CREATE_FROM_SERVICE = 'createFromService';

    /**
     * Advert statuses constants.
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_NEW = 'new';

    /**
     * @var boolean whether advert bookmarked by current user
     */
    public $bookmarked;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advert}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'user_id'], 'required'],
            [['user_id'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id'],'skipOnError' => true],
            [['category_id'], 'exist', 'targetClass' => AdvertCategory::className(), 'targetAttribute' => ['category_id' => 'id'],'skipOnError' => true],
            //[['geography_id'], 'exist', 'targetClass' => , 'targetAttribute' => ['geography_id' => 'id'],'skipOnError' => true],
            ['expiry_at', 'default', 'value' => Yii::$app->formatter->asDate(time() + 3600 * 24 * 30)],
            ['currency_id', 'default', 'value' => Currency::RUB],
            //[['min_price', 'max_price'], 'integer', 'integerOnly' => false],
            [['min_price', 'max_price'], 'validatePrice'],
            [['status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'category_id' => Yii::t('app', 'Категория'),
            'city_id' => Yii::t('app', 'City'),
            'content' => Yii::t('app', 'Содержание'),
            'created_at' => Yii::t('app', 'Создано'),
            'currency_id' => Yii::t('app', 'Валюта'),
            'price' => Yii::t('app', 'Цена'),
            'min_price' => Yii::t('app', 'Минимальная цена'),
            'max_price' => Yii::t('app', 'Максимальная цена'),
            'published' => Yii::t('app', 'Is Published'),
            'status' => Yii::t('app', 'Статус'),
            'expiry_at' => Yii::t('app', 'Срок действия'),
            'type' => Yii::t('app', 'Тип'),
            'updated_at' => Yii::t('app', 'Обновлено'),
        ];
    }

    /**
     * @return array
     */
    public static function attributeLabelsConfig()
    {
        return [
            'status' => [
                self::STATUS_NEW,
                self::STATUS_ACTIVE,
                self::STATUS_BLOCKED
            ]
        ];
    }

    /**
     * @inheritdoc
     * @return AdvertQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdvertQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasMany(AdvertCategory::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(AdvertComment::className(), ['advert_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        // TODO
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeography()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return boolean whether advert in bookmarks by current user
     */
    public function getIsBookmarked()
    {
        // TODO
    }

    public function getCityName()
    {
        return 'Макеевка';
    }

    public function getViewsCount()
    {
        return 0;
    }

    public function getCommentsCount()
    {
        return 0;
    }

    public function getLikesCount()
    {
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if ($this->expiry_at) {
            $this->expiry_at = Yii::$app->formatter->asDate($this->expiry_at, 'php:Y-m-d H:i:s');
        }
        return parent::beforeValidate();
    }

    /**
     * @param string $attribute
     */
    public function validatePrice($attribute)
    {
        if ($attribute == 'max_price') {
            return;
        }
        if ($this->min_price && $this->max_price && $this->min_price > $this->max_price) {
            $this->addError($attribute, AdvertsModule::t('Минимальная цена должна быть меньше максимальной'));
        }
    }

    /**
     * Copy attributes from templet.
     * @param array $attributes
     */
    public function copyFromTemplet($attributes)
    {
        ArrayHelper::remove($attributes, 'id');
        $this->setAttributes($attributes);
    }
}