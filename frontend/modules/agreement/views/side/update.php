<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SideAgr */

$this->title = 'Update Side Agr: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Side Agrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="side-agr-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
