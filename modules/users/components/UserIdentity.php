<?php

namespace app\modules\users\components;

use app\modules\core\db\ActiveRecord;
use app\modules\users\models\ar\EmailConfirmToken;
use app\modules\users\models\ar\PasswordRestoreToken;
use app\modules\users\models\ar\User;
use yii\base\Security;
use yii\db\Expression;
use yii\web\IdentityInterface;
use Yii;

/**
 * Parent class for User model
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $confirmation_token
 * @property integer $status
 * @property integer $superadmin
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property EmailConfirmToken $emailConfirmToken
 * @property PasswordRestoreToken $passwordRestoreToken
 */
abstract class UserIdentity extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmailConfirmToken()
    {
        return $this->hasOne(EmailConfirmToken::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPasswordRestoreToken()
    {
        return $this->hasOne(PasswordRestoreToken::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * Generates email confirmation token.
     * @param string $action
     * @return bool
     */
    public function generateEmailConfirmToken($action)
    {
        if (php_sapi_name() == 'cli') {
            $security = new Security();
            $token = $security->generateRandomString();
        } else {
            $token = Yii::$app->security->generateRandomString();
        }
        $emailConfirmToken = new EmailConfirmToken([
            'user_id' => $this->id,
            'token' => $token,
            'email' => $this->email,
            'expiry_at' => date('Y-m-d H:i:s', time() + 3600 * 24 * 7),
            'subject' => $action,
        ]);
        if ($emailConfirmToken->save()) {
            $this->populateRelation('emailConfirmToken', $emailConfirmToken);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Generates password restore token.
     * @return bool
     */
    public function generatePasswordRestoreToken()
    {
        if (php_sapi_name() == 'cli') {
            $security = new Security();
            $token = $security->generateRandomString();
        } else {
            $token = Yii::$app->security->generateRandomString();
        }
        $passwordRestoreToken = new PasswordRestoreToken([
            'user_id' => $this->id,
            'token' => $token,
        ]);
        if ($passwordRestoreToken->save()) {
            $this->populateRelation('passwordRestoreToken', $passwordRestoreToken);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Finds user by confirmation token.
     * @param string $token confirmation token
     * @return static|null|User
     */
    public static function findByEmailConfirmToken($token)
    {
        return static::find()->innerJoinWith('emailConfirmToken')->where([
            'and', [EmailConfirmToken::tableName() . '.token' => $token], ['>', 'expiry_at', new Expression('NOW()')]
        ])->one();
    }

    /**
     * Finds user by password restore token.
     * @param string $token confirmation token
     * @return static|null|User
     */
    public static function findByPasswordRestoreToken($token)
    {
        return static::find()->innerJoinWith('passwordRestoreToken')->where([
            'and', [PasswordRestoreToken::tableName() . '.token' => $token], ['>', 'expiry_at', new Expression('NOW()')]
        ])->one();
    }

    /**
     * Finds user by confirmation token
     *
     * @param  string      $token confirmation token
     * @return static|null|User
     */
    public static function findInactiveByConfirmationToken($token)
    {
        $expire    = Yii::$app->getModule('user-management')->confirmationTokenExpire;

        $parts     = explode('_', $token);
        $timestamp = (int)end($parts);

        if ( $timestamp + $expire < time() )
        {
            // token expired
            return null;
        }

        return static::findOne([
            'confirmation_token' => $token,
            'status'             => User::STATUS_INACTIVE,
        ]);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        if (php_sapi_name() == 'cli') {
            $security = new Security();
            $this->auth_key = $security->generateRandomString();
        } else {
            $this->auth_key = Yii::$app->security->generateRandomString();
        }
    }

    /**
     * Password validating.
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     */
    public function setPassword($password)
    {
        if (php_sapi_name() == 'cli') {
            $security = new Security();
            $this->password = $security->generatePasswordHash($password);
        } else {
            $this->password = Yii::$app->security->generatePasswordHash($password);
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}