<?php

namespace common\services\jobs;

use Yii;
use yii\base\BaseObject;
use common\models\UserToris;

class TorisAgreementMessageJob extends BaseObject implements \yii\queue\JobInterface
{

    public $link;
    public $aistoken;


    public function execute($queue)
    {
        $moderators = UserToris::find()->where(['iogv_id' => Yii::$app->params['kvs_iogv_id']])->all();

        if($moderators){
            $prtDomain = Yii::$app->params['torisSettings']['full_domain'];	// Адрес портала, см Приложение 6
            //$systemID = 'urn:eis:toris:ummd';	// Код системы
            $systemID = Yii::$app->params['torisSettings']['code'];	// Код системы

            //$aistoken = $_REQUEST["aistoken"];	// aistoken, полученный от ЕСОВ
            $aistoken = $this->aistoken;
            $s_url = $prtDomain . "/api/notifier/";

            $header = array();
            $header[] = 'Content-type: application/json';
            $header[] = 'Authorization: Token ' . $aistoken;
            $header[] = 'SystemID: ' . $systemID;


            foreach ($moderators as $moderator){
                $requestData = array(
                    "message" => "[b]Добавлено новое соглашение[/b] 
                                  [br]<a href='". $this->link ."'></a>. [br] Время: " . date(DATE_RFC2822),
                    "link" => $this->link,
                    "userESOVid" => $moderator->esov_uid
                );

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $s_url);
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

                if (curl_errno($curl)) {
                    Yii::error("Ошибка отправки уведомления сотруднику КВС" . curl_error($curl));
                } else {
                    // Запрос успешно отработан
                }

                curl_close($curl);
            }
        }
    }
}