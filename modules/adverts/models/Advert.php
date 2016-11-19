<?php

namespace app\modules\adverts\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * Class Advert
 * @package app\modules\adverts\models\ar
 */
class Advert extends \yii\db\ActiveRecord
{
    /**
     * Scenarios constants.
     */
    const SCENARIO_CREATE_TEMPLET = 'createTemplet';
    const SCENARIO_UPDATE_TEMPLET = 'updateTemplet';
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
        return 'advert';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $templetExceptScenaios = [
            self::SCENARIO_CREATE_TEMPLET,
            self::SCENARIO_UPDATE_TEMPLET,
        ];

        $rules = [
            // require section
            [['category'], 'required', 'message' => Yii::t('app', 'Select category'), 'except' => $templetExceptScenaios],
            [['city_id'], 'required', 'message' => Yii::t('app', 'Select city'), 'except' => $templetExceptScenaios],
            [['content'], 'required', 'message' => Yii::t('app', 'Fill content'), 'except' => $templetExceptScenaios],
            [['type'], 'required', 'message' => Yii::t('app', 'Select type'), 'except' => $templetExceptScenaios],
            [['term'], 'required', 'message' => Yii::t('app', 'Select term_at'), 'except' => $templetExceptScenaios],

            // required types
            [['min_price', 'max_price'], 'integer'],
            [['min_price'], 'validatePrices'],


            // safe section
            [['status'], 'safe'],
            [['term', 'min_price', 'max_price', 'bookmarked'], 'safe', 'except' => $templetExceptScenaios],

            // default section
            [['category'], 'default', 'value' => Category::DIFFERENT, 'except' => $templetExceptScenaios],
            [['currency'], 'default', 'value' => Currency::RUB, 'except' => $templetExceptScenaios]
        ];

        return $rules;
    }

    /**
     * @return
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return
     */
    public function getGeography()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return array \roman444uk\files\models\File
     */
    public function getFiles()
    {

    }

    /**
     * @return boolean whether advert is a templet
     */
    public function getIsTemplet()
    {
        return (boolean) $this->is_templet;
    }

    /**
     * @return boolean whether advert in bookmarks
     */
    public function getIsBookmarked()
    {
        // TODO
    }

    /**
     * @return array \common\models\File
     */
    public function getComments()
    {
        return $this->hasMany(AdvertComment::className(), ['advert_id' => 'id']);
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'category' => Yii::t('app', 'Category'),
            'city_id' => Yii::t('app', 'City'),
            'content' => Yii::t('app', 'Content'),
            'created_at' => Yii::t('app', 'Created'),
            'price' => Yii::t('app', 'Price'),
            'max_price' => Yii::t('app', 'Min price'),
            'min_price' => Yii::t('app', 'Max price'),
            'published' => Yii::t('app', 'Is Published'),
            'status' => Yii::t('app', 'Status'),
            'term' => Yii::t('app', 'Term'),
            'type' => Yii::t('app', 'Type'),
            'updated_at' => Yii::t('app', 'Updated'),
        ];
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_NEW,
            self::STATUS_ACTIVE,
            self::STATUS_BLOCKED
        ];
    }

    /**
     * @return array
     */
    public static function getTranslatedStatusList()
    {
        $list = [];

        foreach (self::getStatusList() as $status) {
            $list[$status] = Yii::t('app', 'Status '.ucfirst($status));
        }

        return $list;
    }

    /**
     *
     * @param integer $userId
     * @return \app\modules\adverts\models\ar\Advert
     */
    public static function createTemplet($userId)
    {
        return new self([
            'user_id' => $userId,
            'is_templet' => true,
            'scenario' => self::SCENARIO_CREATE_TEMPLET
        ]);
    }
}