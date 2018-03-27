<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Organization */

$this->title = 'Редактировать организацию: ' . $model->name ;
$this->params['breadcrumbs'][] = ['label' => 'Организации', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="organization-update">


    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'cityList' => $cityList
    ]) ?>

</div>
