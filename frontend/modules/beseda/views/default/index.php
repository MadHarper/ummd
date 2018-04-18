<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use kartik\dynagrid\DynaGrid;
USE yii\helpers\Url;
use frontend\core\services\BesedaStatusService;
use frontend\core\services\CheckBesedaControlDate;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\BesedaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Беседы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beseda-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить беседу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>



    <?php
    $columns =
        [
            ['class'=>'kartik\grid\CheckboxColumn', 'order'=>DynaGrid::ORDER_FIX_LEFT],

            //['class'=>'kartik\grid\SerialColumn', 'order'=>DynaGrid::ORDER_FIX_LEFT],

            [
                'attribute'=>'theme',
                'vAlign'=>'middle',
                //'order'=>DynaGrid::ORDER_FIX_LEFT
            ],
            [
                'attribute'=>'target',
                'vAlign'=>'middle',
                //'order'=>DynaGrid::ORDER_FIX_LEFT
            ],
            [
                'attribute'=>'questions',
                'vAlign'=>'middle',
                //'order'=>DynaGrid::ORDER_FIX_LEFT
            ],
            [
                'attribute'=>'address',
                'vAlign'=>'middle',
                //'order'=>DynaGrid::ORDER_FIX_LEFT
            ],
            [
                'attribute'=>'iniciator_id',
                'vAlign'=>'middle',
                'content' => function($data){
                    return $data->iniciator ? $data->iniciator->name : " - ";
                }
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
                            'cancelLabel' => 'Очистить',
                            'format' => 'Y-m-d',
                        ],
                        'opens' => 'left',
                    ],
                    'pluginEvents'=>[
                        "cancel.daterangepicker" => "function() {\$('#agreementsearch-created_at_range').val(''); $('.grid-view').yiiGridView('applyFilter');}",
                    ]
                ])
            ],
            [
                'attribute' => 'control_date',
                'value' => function($data){
                    return date("d.m.Y", strtotime($data->control_date));
                },
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'convertFormat'=>true,
                    'attribute' => 'control_date_range',
                    'pluginOptions' => [
                        'locale' => [
                            'cancelLabel' => 'Очистить',
                            'format' => 'Y-m-d',
                        ],
                        'opens' => 'left',
                    ],
                    'pluginEvents'=>[
                        "cancel.daterangepicker" => "function() {\$('#agreementsearch-created_at_range').val(''); $('.grid-view').yiiGridView('applyFilter');}",
                    ]
                ])
            ],
            [
                'attribute' => 'report_date',
                'value' => function($data){
                    if($data->report_date){
                        return date("d.m.Y", strtotime($data->report_date));
                    }
                    return " - ";
                },
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'convertFormat'=>true,
                    'attribute' => 'report_date_range',
                    'pluginOptions' => [
                        'locale' => [
                            'cancelLabel' => 'Очистить',
                            'format' => 'Y-m-d',
                        ],
                        'opens' => 'left',
                    ],
                    'pluginEvents'=>[
                        "cancel.daterangepicker" => "function() {\$('#agreementsearch-created_at_range').val(''); $('.grid-view').yiiGridView('applyFilter');}",
                    ]
                ])
            ],
            [
                'attribute' => 'status',
                'value' => function($data){
                    return $data->getStatusString();
                },
                'filter' => BesedaStatusService::STATUS_LIST,
            ],

            [
                'attribute' => 'report_overdue',
                'value' => function($data){
                    return $data->report_overdue ? "Нарушен" : "Не нарушен";
                },
                'filter' => [0 => "Срок не нарушен", 1 => "Срок нарушен"],
            ],
            [
                'attribute' => 'agreements',
                'vAlign'=>'middle',
                'label' => 'Соглашения',
                'content' => function ($data) {
                    $str = '';
                    if($data->hasAgreements){
                        $n = 1;
                        foreach ($data->hasAgreements as $agreement){
                            if($agreement->name){
                                $str .= "<div class='intable_list'>";
                                $str .= '<div><span class="badge">' . $n .'</span></div>';
                                $str .= '<div>' . $agreement->name . '</div>';
                                $str .= "</div>";
                                $n++;
                            }
                        }
                    }
                    return $str;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'members',
                'vAlign'=>'middle',
                'label' => 'Участники',
                'content' => function ($data) {
                    $str = '';
                    if($data->employes){
                        $n = 1;
                        foreach ($data->employes as $member){
                            if($member->fio){
                                $str .= "<div class='intable_list'>";
                                $str .= '<div><span class="badge">' . $n .'</span></div>';
                                $str .= '<div>' . $member->fio . '</div>';
                                $str .= "</div>";
                                $n++;
                            }
                        }
                    }
                    return $str;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'orgs',
                'vAlign'=>'middle',
                'label' => 'Организации',
                'content' => function ($data) {
                    $str = '';
                    if($data->employes){
                        $n = 1;
                        foreach ($data->employes as $member){
                            if($member->organization){
                                $str .= "<div class='intable_list'>";
                                $str .= '<div><span class="badge">' . $n .'</span></div>';
                                $str .= '<div>' . $member->organization->name . '</div>';
                                $str .= "</div>";
                                $n++;
                            }
                        }
                    }
                    return $str;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'country',
                'vAlign'=>'middle',
                'label' => 'Страны',
                'content' => function ($data) {
                    $str = '';
                    if($data->employes){
                        $n = 1;
                        foreach ($data->employes as $member){
                            if($member->organization){
                                $str .= "<div class='intable_list'>";
                                $str .= '<div><span class="badge">' . $n .'</span></div>';
                                $str .= '<div>' . $member->organization->country->name . '</div>';
                                $str .= "</div>";
                                $n++;
                            }
                        }
                    }
                    return $str;
                },
                'format' => 'raw',
            ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'dropdown'=>false,
            'urlCreator'=>function($action, $model, $key, $index) {
                switch ($action) {
                    case 'view':
                        $url = Url::toRoute(['/beseda/default/view', 'id' => $model->id]);
                        break;
                    case  'update':
                        $url = Url::toRoute(['/beseda/default/update', 'id' => $model->id]);
                        break;
                    case  'delete'  :
                        $url = Url::toRoute(['/beseda/default/delete', 'id' => $model->id]);
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
        //'storage' => 'db',
        'storage' => 'cookie',
        'gridOptions'=>[
            'dataProvider'=>$dataProvider,
            'filterModel'=>$searchModel,
            'rowOptions' => function ($model, $key, $index, $grid)
            {
                $checkMissionControlDate = new CheckBesedaControlDate();
                if($style = $checkMissionControlDate->checkForGrid($model))
                {
                    return $style;
                }
            },
            'showPageSummary'=>true,
            'floatHeader'=>false,
            'pjax'=>false,
            'responsiveWrap'=>false,
            'panel'=>[
                'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i>  Соглашения</h3>',
                'before' =>  '<div style="padding-top: 7px;"><em></em></div>',
                'after' => false
            ],
            'toolbar' =>  [
                ['content'=>'{dynagrid}'],
                '{export}',
            ]
        ],
        'options'=>['id'=>'dynagrid-beseda'] // a unique identifier is important
    ]);
    if (substr($dynagrid->theme, 0, 6) == 'simple') {
        $dynagrid->gridOptions['panel'] = false;
    }
    DynaGrid::end();
    ?>




</div>
