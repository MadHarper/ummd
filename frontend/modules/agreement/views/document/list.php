<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Документы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>




    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid)
        {
            if($model->status == \common\models\Document::STATUS_NOT_PROCESSED) {
                return ['style' => 'background-color:#f9e4d0;'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'origin_name',
                'filter' => false,
            ],
            [
                'attribute'=>'link',
                'content' => function($data){
                    return "<a href='". $data->link ."' target='_blank'>
                                <i class='fa fa-file-word-o' aria-hidden='true'></i>
                                <span>Скачать</span>
                           </a>";
                },
                'filter' => false,
                'format' => 'html'
            ],
            [
                'attribute'=>'status',
                'content' => function($data){
                    if($data->status === \common\models\Document::STATUS_NOT_PROCESSED){
                        return "<i class='fa fa-hourglass-start' aria-hidden='true'></i>
                                <span class='doc_not_done'>В обработке</span>";
                    }
                    if($data->status === \common\models\Document::STATUS_YES_PROCESSED){
                        return "<i class='fa fa-check-square-o' aria-hidden='true'></i>
                                <span class='doc_done'>Загружен</span>";
                    }
                },
                'filter' => false,
                'format' => 'html'
            ],
            [
                'attribute'=>'created_at',
                'content' => function($data){
                    return date('d.m.Y', $data->created_at);
                },
                'filter' => false,
                //'format' => 'html'
            ],
            //'model',
            //'model_id',
            //'description:ntext',
            //'updated_at',
            //'sea_name',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{delete}',
            ],
        ],
    ]); ?>
</div>


