<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Documents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addDocModal">
            Добавить документ
        </button>
    </p>


    <?php
    \yii\widgets\Pjax::begin([
        'enablePushState' => false,
        'id' => 'agreement_docs'
    ]);?>

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

            //'model',
            //'model_id',
            //'description:ntext',
            'origin_name',
            //'sea_name',
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
            //'created_at',
            //'updated_at',
            [
                'attribute'=>'created_at',
                'content' => function($data){
                    return date('d.m.Y', $data->created_at);
                },
                'filter' => false,
                //'format' => 'html'
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
            ],
        ],
    ]); ?>
</div>


<div class="modal fade bd-example-modal-lg" id="addDocModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Выберите документы формата .docx</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= dosamigos\fileupload\FileUploadUI::widget([
                    'model' => $model,
                    //'name' => 'somefile',
                    'attribute' => 'documentFile',
                    //'url' => ['scene/scene-upload', 'tour_id' => $tour_id],
                    'url' => ['/agreement/document/upload', 'agreementId' => $agreement->id],
                    'gallery' => false,

                    'fieldOptions' => [
                        'accept' => 'file/docx'
                    ],
                    'clientOptions' => [
                        'maxFileSize' => 45000000
                    ],

                    'clientEvents' => [
                        'fileuploaddone' => 'function(e, data) {
                                                $(".btn-upload-close").addClass("uploaded");
                                                console.log(e);
                                                console.log(data);
                                            }',
                        'fileuploadfail' => 'function(e, data) {
                                                console.log(e);
                                                console.log(data);
                                            }',
                    ],
                ]);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<?php

$script = <<< JS
    $('#addDocModal').on('hidden.bs.modal', function (e) {
          $.pjax.reload({container:"#agreement_docs"});
}) 
JS;
$this->registerJs($script, yii\web\View::POS_READY);

?>


<?php \yii\widgets\Pjax::end(); ?>

