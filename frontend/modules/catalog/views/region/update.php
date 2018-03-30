<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Region */

$this->title = 'Редактировать регион: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Субъекты РФ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="region-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
