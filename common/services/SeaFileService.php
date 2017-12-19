<?php

namespace common\services;


use Yii;
use Seafile\Client\Http\Client;
use Seafile\Client\Resource\Library;
use Seafile\Client\Resource\Directory;
use Seafile\Client\Resource\File;
use Seafile\Client\Resource\SharedLink;
use Seafile\Client\Type\SharedLink as SharedLinkType;


class SeaFileService{

    private $_token = null;
    private $_client;
    private $_library;
    private $_directory;
    private $_file;
    private $_directoryItemName;

    const EXPIRE_TIME = 3650;

    public function __construct()
    {
        $this->getToken();
        $this->_client = new Client(
            [
                'base_uri' => Yii::$app->params['sea']['base_path'],
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Token ' . $this->_token
                ],
            ]
        );
        $this->_library     = new Library($this->_client);
        $this->_directory   = new Directory($this->_client);
        $this->_file        = new File($this->_client);
    }



    public function getToken(){

        if(isset($this->_token)){
            return $this->_token;
        }

        $token = json_decode(file_get_contents(Yii::getAlias('@common/seafile/api-token.json')));
        $base_path = Yii::$app->params['sea']['base_path'];

        //Сначала ping что токен валидный
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_path . "/api2/auth/ping/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $headers = array();
        $headers[] = "Authorization: Token " . $token->token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            // ToDo: сгенерировать исключение и записать в лог
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);


        if($result !== '"pong"'){

            // Если не валидный, удаляем старый и получаем новый
            unlink(Yii::getAlias('@common/seafile/api-token.json'));

            // Получаем json с токеном
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $base_path . "/api2/auth-token/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, Yii::$app->params['sea']['pass_phrase']);
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = array();
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                // ToDo: сгенерировать исключение и записать в лог
                return false;
            }


            // открываем файл на запись, если файл не существует, делается попытка создать его
            $fp = fopen(Yii::getAlias('@common/seafile/api-token.json'), "w");

            // записываем в файл текст
            fwrite($fp, $result);

            // закрываем
            fclose($fp);

            $token = json_decode(file_get_contents(Yii::getAlias('@common/seafile/api-token.json')));

            // ToDo: записать в лог "сгенерирован новый токен"
        }

        $this->_token = $token->token;
        return true;
    }



    public function checkOrCreateDir($directoryItemName)
    {

        //$libraryResource = new Library($this->_client);
        $libraryResource = $this->_library;
        //$directoryResource = new Directory($this->_client);
        $directoryResource = $this->_directory;
        $lib = $libraryResource->getById(Yii::$app->params['sea']['libID']);

        $parentDir = '/'; // DirectoryItem must exist within this directory
        if($directoryResource->exists($lib, $directoryItemName, $parentDir) === false) {
            // поддиректория не существует, создаем
            $directory = $directoryItemName; // name of the new Directory
            $recursive = false; // recursive will create parentDir if not already existing
            $success = $directoryResource->create($lib, $directory, $parentDir, $recursive);

            if(!$success){
                //Не смог создать директорию
                //ToDo: сгенерировать исключение, записать в лог
            }
        }

        return true;
    }


    public function uploadToSea($directory, $path, $fileName)
    {
        $fileToUpload = $path;

        $dir = '/' . $directory; // directory in the library to save the file in

        //$fileResource = new File($this->_client);
        $fileResource = $this->_file;
        //$libraryResource = new Library($this->_client);
        $libraryResource = $this->_library;
        $lib = $libraryResource->getById(Yii::$app->params['sea']['libID']);

        $response = $fileResource->upload($lib, $fileToUpload, $dir);

        $uploadedFileId = json_decode((string)$response->getBody());

        // расшариваем файл и получаем ссылку
        $sharedLinkResource = new SharedLink($this->_client);
        $expire = self::EXPIRE_TIME;
        $shareType = SharedLinkType::SHARE_TYPE_DOWNLOAD;

        $p = $dir . "/" . basename($fileName);

        $shareLinkType = $sharedLinkResource->create($lib, $p, $expire, $shareType);
        $link = $shareLinkType->url . "?dl=1";
        return $link;
    }
}