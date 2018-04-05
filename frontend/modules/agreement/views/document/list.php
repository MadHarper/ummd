<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Agreement;
use common\models\Mission;
use common\models\Document;

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
                'attribute' =>'entity_type',
                'label' => 'Связанная сущность',
                'content' => function($data){
                    switch ($data->model) {
                        case Agreement::className() :
                            return "<a href='". Url::to(['/agreement/default/view', 'id' => $data->model_id]) ."'>
                                        <span>Соглашение</span>
                                    </a>";
                            break;
                        case Mission::className() :
                            return "<a href='". Url::to(['/mission/default/view', 'id' => $data->model_id]) ."'>
                                        <span>Командировка</span>
                                    </a>";;
                            break;
                        default:
                            return "";
                    }
                },
                'filter' => false,
            ],
//            [
//                'attribute'=>'status',
//                'content' => function($data){
//                    if($data->status === \common\models\Document::STATUS_NOT_PROCESSED){
//                        return "<i class='fa fa-hourglass-start' aria-hidden='true'></i>
//                                <span class='doc_not_done'>В обработке</span>";
//                    }
//                    if($data->status === \common\models\Document::STATUS_YES_PROCESSED){
//                        return "<i class='fa fa-check-square-o' aria-hidden='true'></i>
//                                <span class='doc_done'>Загружен</span>";
//                    }
//                },
//                'filter' => false,
//                'format' => 'html'
//            ],
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

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        switch ($model->model) {
                            case Agreement::class :
                                $urlView = '/agreement/document/view';
                                break;
                            case Mission::class :
                                $urlView = '/mission/document/view';
                                break;
                            default:
                                $urlView = '/agreement/document/view';
                        }
                        $url = Url::to([$urlView, 'id' => $model->id]);
                        return $url;
                    }

                    if ($action === 'delete') {
                        $url = Url::to(['/agreement/document/delete-list', 'id' => $model->id]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>


