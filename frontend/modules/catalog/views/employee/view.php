<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Employee;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model common\models\Employee */

$this->title = $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Должностные лица', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-view">


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
            'fio',
            'position',
            //'active:boolean',
            [
                'attribute'=>'organization_id',
                'value' => function($model){
                    return $model->organization->name;
                },
            ],
            [
                'attribute'=>'created_at',
                'value' => function($model){
                    $main = Employee::find()->where(['id' => $model->main_id])->one();
                    return $main ? date('d.m.Y', $main->created_at) : date('d.m.Y', $model->created_at);
                },
            ],
            [
                'attribute'=>'updated_at',
                'value' => function($model){
                    return date('d.m.Y', $model->updated_at);
                },
            ],
        ],
    ]) ?>
</div>


<div class="employee-index-history">
    <h3>История:</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'fio',
                'filter' => false,
            ],
            [
                'attribute' => 'position',
                'filter' => false,
            ],
            [
                'attribute'=>'created_at',
                'value' => function($model){
                    return date('d.m.Y', $model->updated_at);
                },
            ],
            [
                'attribute'=>'updated_at',
                'value' => function($model){
                    return date('d.m.Y', $model->updated_at);
                },
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
