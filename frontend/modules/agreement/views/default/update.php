<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\SideAgr;
use common\models\Document;
use common\models\Agreement;

$this->title = 'Редактировать соглашение: ' . $model->shortName;
$this->params['breadcrumbs'][] = ['label' => 'Соглашения', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактировать';
?>


<div class="row">
    <div class="col-lg-5">
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
        <a href="<?= Url::to(['/agreement/document/index', 'agreementId' => $model->id]);?>">
            <div class="widget style1 yellow-bg">
                <div class="row">
                    <div class="col-xs-2">
                        <i class="fa fa-file-word-o fa-4x"></i>
                    </div>
                    <div class="col-xs-10 text-right">
                        <span>Документы</span>
                        <h2 class="font-bold">
                            <?= Document::find()->where(['model' => Agreement::class,'model_id' => $model->id,
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
                <li title="Microsoft Excel 95+"><a class="export-xls" href="<?= Url::to(['/agreement/excell/download', 'agreementId' => $model->id]);?>" target="_blank" tabindex="-1"><i class="text-success glyphicon glyphicon-floppy-remove"></i> Excel</a></li>
            </ul>
        </div>
    </div>
</div>


<div class="agreement-update">


    <?= $this->render('_form', [
        'model' => $model,
        'missionAgreementArr' => $missionAgreementArr,
        'besedaAgreementArr' => $besedaAgreementArr
    ]) ?>

</div>
