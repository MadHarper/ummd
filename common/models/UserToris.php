<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "toris_user".
 *
 * @property integer $id
 * @property integer $bx_id
 * @property string $iogv_id
 * @property string $fio
 * @property string $aistoken
 * @property string $created
 * @property string $updated
 */
class UserToris extends \yii\db\ActiveRecord  implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'toris_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bx_id', 'iogv_id', 'aistoken'], 'required'],
            [['bx_id'], 'integer'],
            [['aistoken'], 'string'],
            [['created', 'updated'], 'safe'],
            [['iogv_id', 'fio'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bx_id' => 'Bx ID',
            'iogv_id' => 'Iogv ID',
            'fio' => 'Fio',
            'aistoken' => 'Aistoken',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    public static function findIdentityByBx($bx_id)
    {
        return static::find()->where([
            'bx_id' => $bx_id
        ])->one();
    }


    public static function createNewBxUser($data): UserToris
    {
        $user = new self();
        $user->setAttributes([
            'fio'      => $data->data->USER_FIO,
            'bx_id'    => $data->data->USER_BX_ID,
            'aistoken' => $data->data->AISTOKEN,
            'iogv_id'  => $data->data->ORG_CODE_IOGV
        ]);
        if ($user->validate() && $user->save(false)) {
            return $user;
        }

        return false;
    }


    /**
     * Returns an ID that can uniquely identify a user identity.
     *
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @inheritdoc
     * @return UserToris|null
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     * The space of such keys should be big enough to defeat potential identity attacks.
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }


    /**
     * Validates the given auth key.
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @param string $authKey the given auth key
     *
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }


    /**
     * Finds an identity by the given token.
     *
     * @param mixed $token the token to be looked for
     * @param mixed $type  the type of the token. The value of this parameter depends on the implementation.
     *                     For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     *
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }
}
