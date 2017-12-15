<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * Toris asset bundle.
 */
class TorisAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/dist';

    public $js = [
        'js/xd_d.js',
        'js/widget.js',
        'https://toris.gov.spb.ru/widget/widget.js'
        //'http://beta.test.toris.vpn/widget/widget.js'
    ];

    public $jsOptions = ['position' => View::POS_HEAD];

    public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
}
