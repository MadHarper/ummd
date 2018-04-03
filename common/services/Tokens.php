<?php

namespace common\services;

use Yii;

class Tokens
{

    private $token_type;
    private $client_secret;
    private $client_id;
    private $grant_type;
    private $expires_in;
    private $token_service;


    public function __construct()
    {
        $this->token_type = 'Bearer';
        $this->client_secret = Yii::$app->params['torisSettings']['secret'];
        $this->client_id = Yii::$app->params['torisSettings']['code'];
        $this->grant_type = 'client_credentials';
        $this->expires_in = '3600';
        $this->token_service = Yii::$app->params['tokens']['baseUrl'];
    }



    public function getAccessToken()
    {
        $header = array();
        $header[] = 'Content-type: application/json; charset=UTF-8';

        $requestData = array(
            "client_id" => $this->client_id,
            "client_secret" => $this->client_secret,
            "grant_type" => "client_credentials",
            "token_type" => "Bearer"
        );


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->token_service);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestData));

        if(isset(\Yii::$app->params['proxySettings'])){
            $proxySettings = \Yii::$app->params['proxySettings'];
            curl_setopt($curl, CURLOPT_PROXY, $proxySettings['host']);
            curl_setopt($curl, CURLOPT_PROXYUSERPWD, $proxySettings['logpass']);
        }

        $data = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($data);

        return $data->access_token ;
    }
}