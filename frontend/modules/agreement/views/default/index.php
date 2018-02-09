<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;
use kartik\select2\Select2;
use common\models\Organization;
use yii\web\JsExpression;
use common\models\Employee;
use common\models\Country;


/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AgreementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Соглашения';
?>
<div class="agreement-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить Соглашение', ['create'], ['class' => 'btn btn-success']) ?>
    </p>



    <?php
        $columns =
            [
                ['class'=>'kartik\grid\CheckboxColumn', 'order'=>DynaGrid::ORDER_FIX_LEFT],

                //['class'=>'kartik\grid\SerialColumn', 'order'=>DynaGrid::ORDER_FIX_LEFT],
                [
                    'attribute'=>'name',
                    'vAlign'=>'middle',
                    //'order'=>DynaGrid::ORDER_FIX_LEFT
                ],
                [
                    'attribute'=>'status',
                    'vAlign'=>'middle',
                    'width'=>'150px',
                    'content' => function($data){
                        return $data->getStatusToString();
                    },
                    'filter' => \common\models\Agreement::getStatusList(),
                    //'order'=>DynaGrid::ORDER_FIX_LEFT
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
                    'attribute'=>'organization_text',
                    'vAlign'=>'middle',
                    'label' => 'Организация',
                    'width'=>'300px',
                    'content' => function ($data) {
                        $str = '';
                        if($data->sideAgrs){
                            $n = 1;

                            foreach ($data->sideAgrs as $side){
                                $str .= "<div class='intable_list'>";
                                $str .= '<div><span class="badge">' . $n .'</span></div>';
                                $str .= '<div>' . $side->org->name . '</div>';
                                $str .= "</div>";
                                $n++;
                            }

                        }

                        return $str;
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute'=>'employee_text',
                    'vAlign'=>'middle',
                    'label' => 'Сотрудники',
                    'width'=>'240px',
                    'content' => function ($data) {
                        $str = '';
                        if($data->sideAgrs){
                            $n = 1;
                            foreach ($data->sideAgrs as $side){
                                if($side->employee){
                                    $str .= "<div class='intable_list'>";
                                    $str .= '<div><span class="badge">' . $n .'</span></div>';
                                    $str .= '<div>' . $side->employee->fio . '</div>';
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
                    'attribute'=>'country',
                    'vAlign'=>'middle',
                    'label' => 'Страны',
                    'width'=>'240px',
                    'content' => function ($data) {
                        $str = '';
                        if($countries = $data->countries){
                            $n = 1;
                            foreach ($countries as $c){
                                $str .= "<div class='intable_list'>";
                                $str .= '<div><span class="badge">' . $n .'</span></div>';
                                $str .= '<div>' . $c->name . '</div>';
                                $str .= "</div>";
                                $n++;
                            }
                        }
                        return $str;
                    },
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model'         => $searchModel,
                        'attribute'     => 'country',
                        'initValueText' => $searchModel->country ? Country::find()
                            ->where(['id' => $searchModel->country])
                            ->one()
                            ->name:
                            "",
                        'pluginOptions' => [
                            'allowClear'         => true,
                            'minimumInputLength' => 3,
                            'ajax'               => [
                                'url'      => '/catalog/organization/searchid',
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
                    'class'=>'kartik\grid\ActionColumn',
                    'dropdown'=>false,
                    'urlCreator'=>function($action, $model, $key, $index) {
                                    switch ($action) {
                                        case 'view':
                                            $url = Url::toRoute(['/agreement/default/view', 'id' => $model->id]);
                                            break;
                                        case  'update':
                                            $url = Url::toRoute(['/agreement/default/update', 'id' => $model->id]);
                                            break;
                                        case  'delete'  :
                                            $url = Url::toRoute(['/agreement/default/delete', 'id' => $model->id]);
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
                    'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i>  Соглашения</h3>',
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
