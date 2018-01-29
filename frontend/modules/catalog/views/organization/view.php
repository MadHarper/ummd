<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model common\models\Organization */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Организации', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-view">


    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'contact:ntext',
            [
                'attribute'=>'country_id',
                'value' => function($model){
                    return $model->country->name;
                },
            ],
            [
                'attribute'=>'created_at',
                'value' => function($model){
                    return date('d.m.Y', $model->created_at);
                },
            ],
            [
                'attribute'=>'updated_at',
                'value' => function($model){
                    return date('d.m.Y', $model->created_at);
                },
            ],
        ],
    ]) ?>

</div>



<div class="org-employee-list">
    <h3>История:</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'filter' => false,
            ],
            [
                'attribute' => 'contact',
                'filter' => false,
            ],
            [
                'attribute' => 'country_id',
                'value' => function($model){
                    return \common\models\Country::find()->where(['id' => $model->country_id])->one()->name;
                },
                'filter' => false,
            ],
            [
                'attribute' => 'iogv',
                'value' => function($model){
                    return $model->iogv ? "Да" : "Нет";
                },
                'filter' => false,
            ],
            [
                'attribute'=>'created_at',
                'value' => function($model){
                    return date('d.m.Y', $model->updated_at);
                },
                'filter' => false,
            ],
            [
                'attribute'=>'updated_at',
                'value' => function($model){
                    return date('d.m.Y', $model->updated_at);
                },
                'filter' => false,
            ],
        ],
    ]); ?>
</div>
