<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Должностные лица';
?>
<div class="employee-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить должностное лицо', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'fio',
            'position',
            //'active:boolean',
            [
                'attribute' => 'organization_id',
                'label' => 'Организация',
                'content' => function ($data) {
                    return $data->organization ? $data->organization->name : '-';
                },
                'filter' => Select2::widget([
                    'model'         => $searchModel,
                    'attribute'     => 'organization_id',
                    'initValueText' => $searchModel->organization_id ? $searchModel->organization->name : "",
                    'pluginOptions' => [
                        'allowClear'         => true,
                        'minimumInputLength' => 3,
                        'ajax'               => [
                            'url'      => '/catalog/employee/searchid',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
