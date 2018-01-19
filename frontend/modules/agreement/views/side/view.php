<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SideAgr */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Side Agrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="side-agr-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'desc:ntext',
            'subdivision:ntext',
            'agreement_id',
            'org_id',
            'employee_id',
        ],
    ]) ?>

</div>