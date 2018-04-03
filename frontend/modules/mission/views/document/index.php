<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\DocumentType;
use frontend\core\helpers\DocTypeHelper;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $searchModel common\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $mission->name . ': Документы';
$this->params['breadcrumbs'][] = ['label' => $mission->name, 'url'=> Url::to(['/mission/default/view', 'id' => $mission->id])];
$this->params['breadcrumbs'][] = 'Документы';
?>
<div class="document-index" id="document-mission-upload" data-url="<?= Url::to(['/mission/document/ajax-upload', 'missionId' => $mission->id]);?>">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div id="add_mission_document">
        <?php $form = ActiveForm::begin(['options' => [
            'enctype' => 'multipart/form-data',
            'name' => 'doc_upload',
            'id' => 'ajax_doc_form',
        ]]);?>


        <?php
        echo $form->field($model, 'document')->widget(FileInput::classname(), [
            'pluginOptions' => [
                //'previewFileType' => 'any',
                'showPreview' => false,
                'showCaption' => true,
                'showUpload' => false,
                //'showRemove' => false,
            ]
        ]);

        echo $form->field($model, 'name')->textInput();

        echo $form->field($model, 'type')->dropDownList(DocumentType::find()->select('name')->where(['visible' => true])->indexBy('id')->column(), []);

        echo $form->field($model, 'date')->widget(DatePicker::classname(), [
            //'options' => ['placeholder' => 'Enter birth date ...'],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'pluginOptions' => [
                'autoclose'=>true,
                'format'    => 'yyyy-mm-dd'
            ]
        ]);

        echo $form->field($model, 'note')->textInput();
        ?>



        <div class="form-group">
            <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary', 'name' => 'upload-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>


    <?php
    \yii\widgets\Pjax::begin([
        'enablePushState' => false,
        'id' => 'mission_docs'
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

            [
                'attribute'=>'name',
                'filter' => false,
            ],

            /*
            [
                'attribute'=>'link',
                'content' => function($data){
                    return "<a href='". Url::to(['/agreement/document/doc-download', 'documentId' => $data->id]) ."' target='_blank' data-pjax='0'>
                                <i class='fa fa-file-word-o' aria-hidden='true'></i>
                                <span>Скачать</span>
                           </a>";
                },
                'filter' => false,
                'format' => 'html'
            ],
            */

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
                'attribute'=>'note',
                'filter' => false,
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
            ],
        ],
    ]); ?>
</div>



<?php \yii\widgets\Pjax::end(); ?>

