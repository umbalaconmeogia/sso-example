<?php

namespace app\models;

use yii\base\NotSupportedException;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $username;

    /**
     * $id is username (get from cookie).
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        \Yii::info("findIdentity($id)", __METHOD__);
        return new static([
            'username' => $id,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        throw new NotSupportedException('"findByUsername" is not implemented.');
    }

    /**
     * Return username (this is used as user id in cookie).
     * {@inheritdoc}
     */
    public function getId()
    {
        \Yii::info("getId()", __METHOD__);
        return $this->username;
    }

    /**
     * Return null. This is not necessary in ServiceProvider.
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        \Yii::info("getAuthKey", __METHOD__);
        return null;
    }

    /**
     * Always return true in ServiceProvider.
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        \Yii::info("validateAuthKey($authKey)", __METHOD__);
        return true;
    }
}
