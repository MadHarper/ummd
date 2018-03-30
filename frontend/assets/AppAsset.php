<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/dist';
    //public $baseUrl = '@web';
    public $css = [
        'css/table.css',
    ];
    public $js = [
        'js/doc_upload.js',
        'js/side_agr.js',
        'js/mission.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];


    public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
    }
}
