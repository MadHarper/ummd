<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Document */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-view">

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить этот документ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute'=>'model',
                'value' => function($model){
                    return HTML::a($model->masterModel->name,['/agreement/default/view', 'id' => $model->model_id]);
                },
                'format' => 'html'
            ],
            'origin_name',
            /*
            [
                'attribute' => 'link',
                'value' => function($model){
                    return "<a href='". $model->link ."' target='_blank'>
                                <i class='fa fa-file-word-o' aria-hidden='true'></i>
                                <span>Скачать</span>
                           </a>";
                },
                'format' => 'html'
            ],
            */
            [
                'attribute' => 'link',
                'value' => function($model){
                    return "<a href='". Url::to(['/agreement/document/doc-download', 'documentId' => $model->id]) ."' target='_blank'>
                                <i class='fa fa-file-word-o' aria-hidden='true'></i>
                                <span>Скачать</span>
                           </a>";
                },
                'format' => 'html'
            ],
            [
                'attribute'=>'created_at',
                //'label' => 'Создан',
                'value' => function($model){
                    return date('d.m.Y', $model->created_at);
                },
            ],
            //'content:ntext',
            //'description:ntext',
            //'sea_name',
            //'visible:boolean',
            //'updated_at',
        ],
    ]) ?>

</div>
