<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use common\models\Agreement;
use common\models\Mission;
use common\models\Beseda;

/* @var $this yii\web\View */
/* @var $model common\models\Document */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => Url::to(['/agreement/document/list'])];
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

                    switch ($model->model) {
                        case Agreement::className():
                            return HTML::a($model->masterModel->generalName,['/agreement/default/view', 'id' => $model->model_id]);
                            break;
                        case Mission::className():
                            return HTML::a($model->masterModel->generalName,['/mission/default/view', 'id' => $model->model_id]);
                            break;
                        case Beseda::className():
                            return HTML::a($model->masterModel->generalName,['/beseda/default/view', 'id' => $model->model_id]);
                            break;
                    }

                },
                'format' => 'html'
            ],
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
