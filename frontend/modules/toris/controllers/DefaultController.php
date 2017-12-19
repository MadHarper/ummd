<?php

namespace frontend\modules\toris\controllers;

use Yii;
use yii\web\Controller;
use common\models\UserToris;
use common\models\AuthAssignment;
use frontend\modules\toris\services\TorisAuthService;
use frontend\modules\toris\Module;
use yii\helpers\Url;
use yii\web\HttpException;

/**
 * Default controller for the `toris` module
 */
class DefaultController extends Controller
{

    public $enableCsrfValidation = false;

    private $_authService;


    public function __construct($id, Module $module, TorisAuthService $authService, array $config = [])
    {
        $this->_authService = $authService;
        parent::__construct($id, $module, $config);
    }


    public function actionAccess()
    {
        $aistoken = Yii::$app->request->get('aistoken');

        if(!$aistoken){
            return $this->redirect(Url::to(['/site/login']));
        }

        $result = $this->_authService->run($aistoken);

        if($result->message !== "OK"){
            return $this->redirect(Url::to(['/site/login']));
        }

        if($this->_authService->authorize($result)){
            return $this->goHome();
        }

        //если пришел false - юзер не имеет ролей
        return $this->redirect(Url::toRoute(['/toris/default/denied']));
    }



    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(Url::to(['/site/login']));
    }



    public function actionCheck()
    {

        // Сюда прилетит запрос, если мы уже будуче залогиненными в торисе попробуем зайти в эту систему.
        // Но! в таком случе перенаправит на site/login и всё, нет ни окна для авторизации через торис
        // (потому что там уже залогинен)
        // Но и у нас пользователь еще не авторизован!


        // поэтому , тут надо проверить авторизован ли пользователь. Если да, освежить аистокен. Нет - кёрлом так же
        // как и в access-экшене
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post('userData');
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($post && isset($post['roles'])){

                if(Yii::$app->user->isGuest){
                    //если пользователь не авторизован у нас, пробуем его авторизовать
                    $result = $this->_authService->run($post['aistoken']);

                    if($result->message == "OK" && $this->_authService->authorize($result)){
                        $res = [
                            'need_redirect' => true,
                            'redirect'      => Url::to(['/agreement/default/index']),
                        ];
                    }else{
                        $res = [
                            'need_redirect' => true,
                            'redirect'      => Url::to(['/toris/default/denied']),
                        ];
                    }
                }else{
                    // если пользователь уже авторизован у нас

                    $user = Yii::$app->user->identity;
                    if($user->aistoken != $post['aistoken']){
                        //в другом окне зашел в другую систему под другим юзером, и у нас стал
                        //другой юзер, возможно не имеющий к нам отношения

                        //старый код
                        //$user->aistoken = $post['aistoken'];
                        //$user->save();

                        //новый код
                        Yii::$app->user->logout();
                        $res = [
                            'need_redirect' => true,
                            'redirect'      => Url::to(['/site/login']),
                        ];
                    }

                    //$res = ['need_redirect' => false];
                }

                return $res;
            }

            //если пользователь не наш
            $res = [
                'need_redirect' => true,
                //'redirect'      => Url::toRoute(['/site/denied'])
                'redirect'      => Url::toRoute(['/toris/default/denied'])
            ];

            return $res;
        }
    }

    public function actionDenied(){
        $this->layout = 'denied_layout';

        $view = $this->getView();
        \frontend\assets\TorisDeniedAsset::register($view);
        $return_url = Url::toRoute(['/toris/default/access'], true);
        $logout_url = Url::toRoute(['/toris/default/logout'], true);

        $torisSevice = Yii::createObject(TorisAuthService::class);

        $torisDomain = $torisSevice->getDomain();
        $torisCode = $torisSevice->getCode();

        $view->registerJs('TorisWidget.init("' . $return_url . '", "' . $logout_url . '", true, "'.$torisDomain.'", "'. $torisCode .'");');


        return $this->render('denied');
    }

}
