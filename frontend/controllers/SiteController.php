<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use frontend\assets\TorisAsset;
use frontend\modules\toris\services\TorisAuthService;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /*
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            */
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        //Оставить
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }


        $this->layout = 'login_empty';
        $view = $this->getView();
        // Убираем старые js для входа в ТОРИС
        $view->js = [];

        TorisAsset::register($view);
        $return_url = Url::toRoute(['/toris/default/access'], true);
        $logout_url = Url::toRoute(['/toris/default/logout'], true);

        $torisSevice = Yii::createObject(TorisAuthService::class);

        $torisDomain = $torisSevice->getDomain();
        $torisCode = $torisSevice->getCode();

        $view->registerJs('TorisWidget.init("' . $return_url . '", "' . $logout_url . '", true, "'.$torisDomain.'", "'. $torisCode .'");');
        return $this->render('login');
    }



    public function actionDenied()
    {
        //ToDo: подумать как подключить торис-виджет (панель). Как в контроллере выше - не получится - зацикливается

        $this->layout = 'login_empty';
        return $this->render('401');
    }

}
