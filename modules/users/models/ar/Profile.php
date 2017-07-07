<?php

namespace app\modules\users\models\ar;

use app\modules\core\validators\UrlValidator;
use app\modules\users\UsersModule;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $fullName
 * @property string $name
 * @property string $number_skype
 * @property string $number_isq
 * @property string $page_fb
 * @property string $page_ok
 * @property string $page_vk
 * @property string $patronymic
 * @property string $preferable_connection_type
 * @property string $surname
 * @property integer $user_id
 */
class Profile extends \app\modules\core\db\ActiveRecord
{
    const CONNECTION_TYPE_PHONE = 'phone';
    const CONNECTION_TYPE_SKYPE = 'skype';
    const CONNECTION_TYPE_ISQ = 'isq';
    const CONNECTION_TYPE_SOCIAL = 'social';

    const LOCALE_RU = 'ru';
    const LOCALE_UA = 'ua';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['phone_1', 'phone_2', 'phone_3', 'skype', 'isq'], 'string', 'max' => 32],
            [['page_vk', 'page_fb', 'page_ok'], 'string', 'max' => 64],
            [['page_vk'], UrlValidator::className(), 'defaultScheme' => 'http', 'host' => 'vk.com'],
            [['page_fb'], UrlValidator::className(), 'defaultScheme' => 'http', 'host' => 'facebook.com'],
            [['page_ok'], UrlValidator::className(), 'defaultScheme' => 'http', 'host' => 'ok.ru'],
            /*
            [['firstname', 'middlename', 'lastname', 'avatar_path', 'avatar_base_url'], 'string', 'max' => 255],
            ['locale', 'in', 'range' => array_keys(self::getAttributeLabels('locale'))],
            ['locale', 'default', 'value' => Yii::$app->language],
            */
            [[
                'phone_1', 'phone_2', 'phone_3', 'skype', 'isq', 'page_vk', 'page_fb', 'page_ok', 'preferable_connection_type'
            ], 'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fullName' => UsersModule::t('ФИО'),
            'name' => UsersModule::t('Имя'),
            'isq' => UsersModule::t('ISQ'),
            'skype' => UsersModule::t('Скайп'),
            'page_fb' => UsersModule::t('Страница в facebook'),
            'page_ok' => UsersModule::t('Страница в одноклассниках'),
            'page_vk' => UsersModule::t('Страница в контакте'),
            'patronymic' => UsersModule::t('Отчество'),
            'phone_1' => UsersModule::t('Телефон №1'),
            'phone_2' => UsersModule::t('Телефон №2'),
            'phone_3' => UsersModule::t('Телефон №3'),
            'preferable_connection_type' => UsersModule::t('Предпочтительный тип связи'),
            'surname' => UsersModule::t('Имя'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function attributeLabelsConfig()
    {
        return [
            'locale' => [
                self::LOCALE_RU => 'Русский',
                self::LOCALE_UA => 'Украинский',
            ],
            'preferable_connection_type' => [
                self::CONNECTION_TYPE_ISQ => 'ISQ',
                self::CONNECTION_TYPE_PHONE => 'Телефон',
                self::CONNECTION_TYPE_SKYPE => 'Skype',
                self::CONNECTION_TYPE_SOCIAL => 'Соц. страница',
            ]
        ];
    }
    
    /**
     * @return string full user name
     */
    public function getFullName()
    {
        if ($this->name || $this->surname) {
            return implode(' ', [$this->name, $this->surname]);
        }
        return '';
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhones()
    {
        return $this->hasMany(ProfilePhone::className(), ['user_id' => 'user_id']);
    }
}