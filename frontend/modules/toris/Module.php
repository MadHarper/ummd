<?php

namespace frontend\modules\toris;

use yii\base\BootstrapInterface;
use Yii;
use frontend\modules\toris\services\TorisAuthService;

/**
 * toris module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\toris\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }


    public function bootstrap($app)
    {
        Yii::$container->setSingleton(TorisAuthService::class, [], [
            Yii::$app->params['torisSettings']['domain'],
            Yii::$app->params['torisSettings']['code'],
            Yii::$app->params['torisSettings']['urn']
        ]);
    }

}
