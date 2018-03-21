<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DocumentType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы документов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-type-view">


    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить этот тип?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'visible:boolean',
        ],
    ]) ?>

</div>
