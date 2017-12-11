<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\SideAgr;
/* @var $this yii\web\View */
/* @var $model common\models\Agreement */



$this->title = 'Редактировать соглашение: ' . $model->shortName;
$this->params['breadcrumbs'][] = ['label' => 'Соглашения', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактировать';
?>


<div class="row">
    <div class="col-lg-6">
    </div>

    <div class="col-lg-3">
        <a href="<?= Url::to(['/agreement/side/index', 'agreementId' => $model->id]);?>">
            <div class="widget style1 lazur-bg">
                <div class="row">
                    <div class="col-xs-2">
                        <i class="fa fa-user-o fa-4x"></i>
                    </div>
                    <div class="col-xs-10 text-right">
                        <span>Стороны соглашения</span>
                        <h2 class="font-bold"><?= SideAgr::find()->where(['agreement_id' => $model->id])->count();?></h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3">
        <a href="#">
            <div class="widget style1 yellow-bg">
                <div class="row">
                    <div class="col-xs-2">
                        <i class="fa fa-file-word-o fa-4x"></i>
                    </div>
                    <div class="col-xs-10 text-right">
                        <span>Документы</span>
                        <h2 class="font-bold">4</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>


<div class="agreement-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
