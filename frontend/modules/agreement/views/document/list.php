<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Документы';
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


            //'name',
            [
                'attribute'=>'name',
                'filter' => false,
            ],
            [
                'attribute'=>'doc_type_id',
                'content' => function($data){
                    if($data->doc_type_id){
                        return $data->docType->name;
                    }
                    return "";
                },
                'filter' => false,
            ],
            /*
            [
                'attribute'=>'origin_name',
                'content' => function($data){
                    return $data->origin_name . "." . $data->type;
                },
                'filter' => false,
            ],
            */
            [
                'attribute'=>'doc_date',
                'content' => function($data){
                    if($data->doc_date){
                        return date('d.m.Y', strtotime($data->doc_date));
                    }
                    return "";
                },
                'filter' => false,
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
                'attribute'=>'note',
                'filter' => false,
            ],
            [
                'attribute'=>'link',
                'content' => function($data){
                    if($data->type === "docx"){
                        return "<a href='". $data->link ."' target='_blank'>
                                <i class='fa fa-file-word-o' aria-hidden='true'></i>
                                <span>Скачать</span>
                           </a>";
                    }

                    return "<a href='". $data->link ."' target='_blank'>
                                <i class='fa fa-file-image-o' aria-hidden='true'></i>
                                <span>Скачать</span>
                           </a>";
                },
                'filter' => false,
                'format' => 'html'
            ],
            /*
            [
                'attribute'=>'created_at',
                'content' => function($data){
                    return date('d.m.Y', $data->created_at);
                },
                'filter' => false,
            ],
            */

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{delete}',
            ],
        ],
    ]); ?>
</div>


