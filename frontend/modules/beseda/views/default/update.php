<?php

use yii\helpers\Html;
use common\models\BesedaEmployee;
use common\models\Beseda;
use common\models\Document;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\Beseda */

$this->title = 'Редактировать беседу: ' . $model->theme;
$this->params['breadcrumbs'][] = ['label' => 'Беседы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>

<div class="row">
    <div class="col-lg-5">
    </div>

    <div class="col-lg-3">
        <a href="<?= Url::to(['/beseda/member/index', 'besedaId' => $model->id]);?>">
            <div class="widget style1 lazur-bg">
                <div class="row">
                    <div class="col-xs-2">
                        <i class="fa fa-user-o fa-4x"></i>
                    </div>
                    <div class="col-xs-10 text-right">
                        <span>Участники</span>
                        <h2 class="font-bold">
                            <?= BesedaEmployee::find()->where(['beseda_id' => $model->id])->count();?>
                        </h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3">
        <a href="<?= Url::to(['/beseda/document/index', 'besedaId' => $model->id]);?>">
            <div class="widget style1 yellow-bg">
                <div class="row">
                    <div class="col-xs-2">
                        <i class="fa fa-file-word-o fa-4x"></i>
                    </div>
                    <div class="col-xs-10 text-right">
                        <span>Документы</span>
                        <h2 class="font-bold">
                            <?= Document::find()->where(['model' => Beseda::class,'model_id' => $model->id,
                                'visible' => true])
                                ->count();?>
                        </h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-1">
        <div class="btn-group excell_entity_export">
            <button id="w10" class="btn btn-default dropdown-toggle" title="Экспорт" data-toggle="dropdown" aria-expanded="true"><i class="glyphicon glyphicon-export"></i>  <span class="caret"></span></button>

            <ul id="w11" class="dropdown-menu dropdown-menu-right"><li role="presentation" class="dropdown-header">Экспорт данных с текущей страницы</li>
                <li title="Microsoft Excel 95+"><a class="export-xls" href="<?= Url::to(['/mission/excell/download', 'missionId' => $model->id]);?>" target="_blank" tabindex="-1"><i class="text-success glyphicon glyphicon-floppy-remove"></i> Excel</a></li>
            </ul>
        </div>
    </div>
</div>




<div class="beseda-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'availableStatuses' => $availableStatuses,
        'besedaAgreementArr' => $besedaAgreementArr
    ]) ?>

</div>
