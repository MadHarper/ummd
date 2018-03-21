<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DocumentType */

$this->title = 'Редактировать тип документа: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы документов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="document-type-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
