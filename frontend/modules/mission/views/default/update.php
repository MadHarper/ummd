<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Document;
use common\models\Mission;
use common\models\MissionEmployee;

/* @var $this yii\web\View */
/* @var $model common\models\Mission */

$this->title = 'Update Mission: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Missions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="row">
    <div class="col-lg-3">
    </div>

    <div class="col-lg-3">
        <a href="<?= Url::to(['/mission/member/index', 'missionId' => $model->id]);?>">
            <div class="widget style1 lazur-bg">
                <div class="row">
                    <div class="col-xs-2">
                        <i class="fa fa-user-o fa-4x"></i>
                    </div>
                    <div class="col-xs-10 text-right">
                        <span>Участники</span>
                        <h2 class="font-bold">
                            <?= MissionEmployee::find()->where(['mission_id' => $model->id])->count();?>
                        </h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3">
        <a href="<?= Url::to(['/mission/document/index', 'missionId' => $model->id]);?>">
            <div class="widget style1 yellow-bg">
                <div class="row">
                    <div class="col-xs-2">
                        <i class="fa fa-file-word-o fa-4x"></i>
                    </div>
                    <div class="col-xs-10 text-right">
                        <span>Документы</span>
                        <h2 class="font-bold">
                            <?= Document::find()->where(['model' => Mission::class,'model_id' => $model->id,
                                'visible' => true])
                                ->count();?>
                        </h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3">
        <a href="#">
            <div class="widget style1 blue-bg">
                <div class="row">
                    <div class="col-xs-2">
                        <i class="fa fa-handshake-o fa-4x"></i>
                    </div>
                    <div class="col-xs-10 text-right">
                        <span>Соглашения</span>
                        <h2 class="font-bold">
                            12
                        </h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>


<div class="mission-update">

    <?= $this->render('_form', [
        'model' => $model,
        'iogvList' => $iogvList,
    ]) ?>

</div>