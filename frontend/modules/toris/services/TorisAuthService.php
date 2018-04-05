<?php

namespace frontend\modules\toris\services;

use common\models\UserToris;
use common\models\AuthAssignment;
use Yii;

class TorisAuthService
{

    private $_domain;
    private $_code;
    private $_urn;


    const ROLE_ADMIN = '[urn:eis:toris:ummd]urn:role:ummd:admin';
    const ROLE_MODERATOR = '[urn:eis:toris:ummd]urn:role:ummd:moderator';
    const ROLE_USER = '[urn:eis:toris:ummd]urn:role:ummd:user';

    const ROLES_ARRAY = [
        self::ROLE_ADMIN            => 'Администратор',
        self::ROLE_MODERATOR        => 'Сотрудник КВС',
        self::ROLE_USER             => 'Пользователь',
    ];

    const ROLES_SHORT_NAMES = [
        self::ROLE_ADMIN            => 'admin',
        self::ROLE_MODERATOR        => 'moderator',
        self::ROLE_USER             => 'user',
    ];

    public function __construct(string $domain, string $code, string $urn)
    {
        $this->_domain = $domain;
        $this->_code = $code;
        $this->_urn = $urn;
    }


    public function getDomain()
    {
        return $this->_domain;
    }

    public function getCode()
    {
        return $this->_code;
    }

    public function run($aistoken)
    {
        $s_url = $this->_domain . $this->_urn;

        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: Token ' . $aistoken;
        $header[] = 'SystemID: ' . $this->_code;

        $curl = curl_init();

        if ($curl === false) {
            return false;
        }

        curl_setopt($curl, CURLOPT_URL, $s_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        if(isset(\Yii::$app->params['proxySettings'])){
            $proxySettings = \Yii::$app->params['proxySettings'];
            curl_setopt($curl, CURLOPT_PROXY, $proxySettings['host']);
            curl_setopt($curl, CURLOPT_PROXYUSERPWD, $proxySettings['logpass']);
        }

        $data = curl_exec($curl);

        if (curl_errno($curl)) {
            return false;
        }

        $result = json_decode($data);
        curl_close($curl);
        return $result;
    }


    public function authorize($result)
    {
        if(!$result->data->USER_ROLES){
            return false;
        }

        $user = UserToris::findIdentityByBx($result->data->USER_BX_ID);

        //освежаем токен существующего юзера, а если такого юзера нет, создаем со всеми пришедшими аттрибутами
        if($user){
            $user->aistoken = $result->data->AISTOKEN;
            $user->save();
        }else{
            $user = UserToris::createNewBxUser($result);
        }

        $sended_roles = $result->data->USER_ROLES;


        $new_roles = [];

        foreach ($sended_roles as $role){
            if(array_key_exists($role,self::ROLES_SHORT_NAMES )){
                $new_roles[] = self::ROLES_SHORT_NAMES[$role];
            }
        }

        if(empty($new_roles)){
            return false;
        }

        $auth = Yii::$app->authManager;
        $old_roles = AuthAssignment::find()->where(['user_id' => $user->id])->indexBy('item_name')->all();

        if($old_roles){
            foreach ($old_roles as $old_role){
                if(!in_array($old_role->item_name, $new_roles)){
                    $old_role->delete();
                }
            }

            foreach ($new_roles as $new_role){
                if(!array_key_exists($new_role, $old_roles)){
                    $role = $auth->getRole($new_role);
                    $auth->assign($role, $user->id);
                }
            }
        }else{
            foreach ($new_roles as $new_role){
                $role = $auth->getRole($new_role);
                $auth->assign($role, $user->id);
            }
        }

        //@session_regenerate_id(true);

        Yii::$app->user->login($user, 3600 * 24 * 30);

        return true;
    }
}