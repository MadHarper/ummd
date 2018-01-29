<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrganizationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Организации';
?>
<div class="organization-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить организацию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'contact:ntext',
            [
                'attribute' => 'country_id',
                'label' => 'Страна',
                'content' => function ($data) {
                    return $data->country ? $data->country->name : '-';
                },
                'filter' => Select2::widget([
                    'model'         => $searchModel,
                    'attribute'     => 'country_id',
                    'initValueText' => $searchModel->country_id ? $searchModel->country->name : "",
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
