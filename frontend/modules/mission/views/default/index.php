<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Missions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mission-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Mission', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name:ntext',
            'date_start',
            'date_end',
            'country_id',
            //'region_id',
            //'city_id',
            //'order',
            //'target:ntext',
            //'iogv_id',
            //'duty_man_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>