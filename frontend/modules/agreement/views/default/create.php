<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Agreement */

$this->title = 'Добавить соглашение';
$this->params['breadcrumbs'][] = ['label' => 'Соглашения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
