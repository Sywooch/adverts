<?php

namespace app\modules\users\models\ar;

use app\modules\core\behaviors\TimestampBehavior;
use app\modules\core\db\ActiveRecord;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "auth_fail".
 *
 * @property integer $id
 * @property string $action
 * @property string $email
 * @property string $ip
 * @property string $created_at
 */
class AuthFail extends ActiveRecord
{
    const ACTION_LOGIN = 'login';
    const ACTION_REGISTRATION = 'registration';
    const ACTION_PASSWORD_RESTORE = 'passwordRestore';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_fail}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => null,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'string', 'max' => 128],
            [['ip'], 'ip'],
            [['action'], 'in', 'range' => array_keys(self::getAttributeLabels('action'))],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'ip' => Yii::t('app', 'Ip'),
            'action' => Yii::t('app', 'Action'),
            'created_at' => Yii::t('app', 'Last Attempt At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function attributeLabelsConfig()
    {
        return [
            'action' => [
                self::ACTION_LOGIN => 'Авторизация',
                self::ACTION_REGISTRATION => 'Регистрация',
                self::ACTION_PASSWORD_RESTORE => 'Восстановление пароля',
            ]
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\users\models\aq\AuthFailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\users\models\aq\AuthFailQuery(get_called_class());
    }

    /**
     * @param string $action
     * @param $ip
     * @param $email
     * @return bool
     */
    public static function create($action = self::ACTION_LOGIN, $ip, $email)
    {
        $authFail = new self([
            'action' => $action,
            'email' => $email,
            'ip' => $ip,
        ]);
        return $authFail->save();
    }

    /**
     * @param string $action
     * @param $email
     * @return int|string
     */
    public static function getEmailCount($action, $email)
    {
        $result = static::find()->select([
            'COUNT(id) AS count', new Expression('UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(MAX(created_at)) AS timeout'),
        ])->where([
            'and',
            ['email' => $email],
            ['action' => $action],
            ['>', 'created_at', new Expression('NOW() - INTERVAL 1 MINUTE')],
        ])->orderBy('created_at DESC')->asArray()->one();
        $sql = static::find()->select([
            'COUNT(id) AS count', new Expression('UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(MAX(created_at)) AS timeout'),
        ])->where([
            'and',
            ['email' => $email],
            ['action' => $action],
            ['>', 'created_at', new Expression('NOW() - INTERVAL 1 MINUTE')],
        ])->orderBy('created_at DESC')->createCommand()->getRawSql();
        return $result;
    }

    /**
     * @param string $action
     * @param string $ip
     * @return int|string
     */
    public static function getIpCount($action, $ip)
    {
        $result = static::find()->select([
            'COUNT(id) AS count', new Expression('UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(MAX(created_at)) AS timeout'),
        ])->where([
            'and',
            ['ip' => $ip],
            ['action' => $action],
            ['>', 'created_at', new Expression('NOW() - INTERVAL 1 MINUTE')],
        ])->orderBy('created_at DESC')->asArray()->one();
        return $result;
    }

    /**
     * Reset all counters.
     * @param string $action
     * @param string $ip
     * @param string $email
     */
    public static function resetCounters($action, $ip, $email)
    {
        static::deleteAll(['or', ['action' => $action], ['ip' => $ip], ['email' => $email]]);
    }

    /**
     * @param string $action
     * @param $ip
     * @param $email
     * @return null
     */
    public static function getTimeout($action, $ip, $email)
    {
        $emailData = self::getEmailCount($action, $email);
        if ($emailData['count'] >= 3) {
            return (60 - $emailData['timeout']);
        }
        $ipData = self::getIpCount($action, $ip, $email);
        if ($ipData['count'] >= 3) {
            return (60 - $ipData['timeout']);
        }
        return null;
    }
}
