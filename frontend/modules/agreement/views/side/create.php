<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SideAgr */

$this->title = 'Добавить сторону соглашения';
$this->params['breadcrumbs'][] = ['label' => 'Side Agrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="side-agr-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
