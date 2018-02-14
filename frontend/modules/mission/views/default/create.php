<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Mission */

$this->title = 'Добавить командировку';
$this->params['breadcrumbs'][] = ['label' => 'Командировки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mission-create">

    <?= $this->render('_form', [
        'model' => $model,
        'iogvList' => $iogvList,
        'missionAgreementArr' => $missionAgreementArr,
        'historyOrgOptions' => $historyOrgOptions,
        'nonHistoryOrgOptions' => $nonHistoryOrgOptions
    ]) ?>

</div>