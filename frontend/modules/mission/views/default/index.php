<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use common\models\Organization;
use yii\web\JsExpression;
use common\models\Employee;
use common\models\Country;
use yii\helpers\Url;



$this->title = 'Missions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mission-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Mission', ['create'], ['class' => 'btn btn-success']) ?>
    </p>




    <?php
    $columns =
        [
            ['class'=>'kartik\grid\CheckboxColumn', 'order'=>DynaGrid::ORDER_FIX_LEFT],

            [
                'attribute'=>'name',
                'vAlign'=>'middle',
            ],
            [
                'attribute' => 'date_start',
                'value' => function($data){
                    return date("d.m.Y", strtotime($data->date_start));
                },
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'convertFormat'=>true,
                    'attribute' => 'created_at_range',
                    'pluginOptions' => [
                        'locale' => [
                            'cancelLabel' => 'Clear',
                            'format' => 'Y-m-d',
                        ],
                        'opens' => 'left',
                    ]
                ])
            ],
            [
                'attribute' => 'date_end',
                'value' => function($data){
                    return date("d.m.Y", strtotime($data->date_end));
                },
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'convertFormat'=>true,
                    'attribute' => 'ended_at_range',
                    'pluginOptions' => [
                        'locale' => [
                            'cancelLabel' => 'Clear',
                            'format' => 'Y-m-d',
                        ],
                        'opens' => 'left',
                    ]
                ])
            ],
            [
                'attribute'=>'iogv_id',
                'vAlign'=>'middle',
                'label' => 'ИОГВ',
                'width'=>'300px',
                'content' => function ($data) {
                    return $data->iogv_id ? $data->iogv->name : "-";
                },
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model'         => $searchModel,
                    'attribute'     => 'iogv_id',
                    'data' => $iogvList,
                    'options' => ['placeholder' => 'Выберите ИОГВ ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],

           [
               'attribute'=>'duty_man_id',
               'vAlign'=>'middle',
               'label' => 'Ответственный',
               'width'=>'240px',
               'content' => function ($data) {
                   return $data->duty_man_id ?  $data->dutyMan->fio : "-";
               },
               'format' => 'raw',
               'filter' => Select2::widget([
                   'model'         => $searchModel,
                   'attribute'     => 'duty_man_id',
                   'initValueText' => $searchModel->duty_man_id ? Employee::find()
                       ->where(['id' => $searchModel->duty_man_id])
                       ->one()
                       ->fio:
                       "",
                   'pluginOptions' => [
                       'allowClear'         => true,
                       'minimumInputLength' => 3,
                       'ajax'               => [
                           'url'      => '/mission/default/search-employee',
                           'dataType' => 'json',
                           'data'     => new JsExpression('function(params) { return {q:params.term}; }')
                       ],
                       'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
                       'templateResult'     => new JsExpression('function(city) {  return city.text; }'),
                       'templateSelection'  => new JsExpression('function (city) { console.log(city); return  city.text; }'),
                   ],
                   'options'       => [
                       'placeholder' => '',
                   ]
               ]),
           ],
           [
               'attribute'=>'country_id',
               'vAlign'=>'middle',
               'label' => 'Страна',
               'width'=>'240px',
               'content' => function ($data) {
                   return $data->country_id ? $data->country->name : "-";
               },
               'format' => 'raw',
               'filter' => Select2::widget([
                   'model'         => $searchModel,
                   'attribute'     => 'country_id',
                   'data' => $countryList,
                   'options' => ['placeholder' => 'Выберите Страну ...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]),
           ],





            [
                'class'=>'kartik\grid\ActionColumn',
                'dropdown'=>false,
                'urlCreator'=>function($action, $model, $key, $index) {
                    switch ($action) {
                        case 'view':
                            $url = Url::toRoute(['/mission/default/view', 'id' => $model->id]);
                            break;
                        case  'update':
                            $url = Url::toRoute(['/mission/default/update', 'id' => $model->id]);
                            break;
                        case  'delete'  :
                            $url = Url::toRoute(['/mission/default/delete', 'id' => $model->id]);
                            break;
                    }

                    return $url;
                },
                'viewOptions'=>['title'=> 'Просмотр', 'data-toggle'=>'tooltip'],
                'updateOptions'=>['title'=> 'Редактирование', 'data-toggle'=>'tooltip'],
                'deleteOptions'=>['title'=> 'Удаление', 'data-toggle'=>'tooltip'],
                'order'=>DynaGrid::ORDER_FIX_RIGHT
            ],
        ];
    $dynagrid = DynaGrid::begin([
        'columns' => $columns,
        'theme'=>'panel-info',
        'showPersonalize'=>true,
        'allowThemeSetting' => false,
        'allowFilterSetting' => false,
        'allowSortSetting' => false,
        'storage' => 'session',
        'gridOptions'=>[
            'dataProvider'=>$dataProvider,
            'filterModel'=>$searchModel,
            'showPageSummary'=>true,
            'floatHeader'=>false,
            'pjax'=>false,
            'responsiveWrap'=>false,
            'panel'=>[
                'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i>  Командировки</h3>',
                'before' =>  '<div style="padding-top: 7px;"><em></em></div>',
                'after' => false
            ],
            'toolbar' =>  [
                ['content'=>'{dynagrid}'],
                '{export}',
            ]
        ],
        'options'=>['id'=>'dynagrid-1'] // a unique identifier is important
    ]);
    if (substr($dynagrid->theme, 0, 6) == 'simple') {
        $dynagrid->gridOptions['panel'] = false;
    }
    DynaGrid::end();

    ?>


</div>