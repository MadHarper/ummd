<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SideAgrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Стороны соглашения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="side-agr-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить сторону соглашения', ['/agreement/side/create', 'agreementId' => $agreement->id], ['class' => 'btn btn-success']) ?>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'desc:ntext',
            //'subdivision:ntext',
                        [
                'attribute' => 'org_id',
                'content' => function ($data) {
                    return $data->org ? $data->org->name : '-';
                },
                'filter' => false,

            ],
            [
                'attribute' => 'employee_id',
                'content' => function ($data) {
                    return $data->employee ? $data->employee->fio : '-';
                },
                'filter' => false,

            ],
            [
                'attribute' => 'desc',
                'filter' => false,
            ],
            [
                'attribute' => 'subdivision',
                'filter' => false,
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
