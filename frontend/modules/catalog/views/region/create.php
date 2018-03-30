<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Region */

$this->title = 'Добавить регион';
$this->params['breadcrumbs'][] = ['label' => 'Субъекты РФ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
