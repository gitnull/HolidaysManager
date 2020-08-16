<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $surname
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property int $created_at
 * @property int $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            'auth_key' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'auth_key'
                ],
                'value' => Yii::$app->getSecurity()->generateRandomString()
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password_hash', 'name', 'surname'], 'required'],
            ['password', 'required', 'on' => 'create'],
            [['created_at', 'updated_at'], 'integer'],
            [['email', 'name', 'surname'], 'string', 'max' => 100],
            [['auth_key', 'password', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'E-mail',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'auth_key' => 'Auth Key',
            'password' => 'Пароль',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Проверка пароля
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Генерация хэша пароля
     *
     * @param $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Пользователь по e-mail
     * @param $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Полное имя
     * @return string
     */
    public function getFullName()
    {
        return $this->name . ' ' . $this->surname;
    }
}
