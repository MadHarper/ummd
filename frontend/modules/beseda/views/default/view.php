<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Beseda */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Besedas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beseda-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'theme:ntext',
            'target:ntext',
            'created_at',
            'updated_at',
            'date_start',
            'date_start_time',
            'iniciator_id',
            'report_date',
            'control_date',
            'status',
            'notes:ntext',
            'iogv_id',
        ],
    ]) ?>

</div>
