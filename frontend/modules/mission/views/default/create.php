<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Mission */

$this->title = 'Create Mission';
$this->params['breadcrumbs'][] = ['label' => 'Missions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mission-create">

    <?= $this->render('_form', [
        'model' => $model,
        'iogvList' => $iogvList,
        'missionAgreementArr' => $missionAgreementArr
    ]) ?>

</div>