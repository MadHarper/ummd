<?php
/**
 * Created by PhpStorm.
 * User: y0rker
 * Date: 29.09.17
 * Time: 13:21
 */

namespace frontend\components;


use Yii;
use yii\helpers\Url;
use frontend\assets\TorisAsset;
use frontend\modules\toris\services\TorisAuthService;

/**
 * Class Controller
 *
 * @package backend\components
 */
class BaseController extends \yii\web\Controller
{



    public function beforeAction($action)
    {
        if (!Yii::$app->request->isAjax) {
            $view = $this->getView();
            TorisAsset::register($view);
            $return_url = Url::toRoute(['/toris/default/access'], true);
            $logout_url = Url::toRoute(['/toris/default/logout'], true);

            $torisSevice = Yii::createObject(TorisAuthService::class);

            $torisDomain = $torisSevice->getDomain();
            $torisCode = $torisSevice->getCode();

            $view->registerJs('TorisWidget.init("' . $return_url . '", "' . $logout_url . '", true, "'.$torisDomain.'", "'. $torisCode .'");');
        }
        return parent::beforeAction($action);
    }


}
