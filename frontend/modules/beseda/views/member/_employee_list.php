<?php

use yii\grid\GridView;


?>

<div id="mission_emp_grid">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'ИОГВ',
                'value' => function($data){
                    return $data->employee->organization->name;
                },
                'filter' => false,
            ],

            [
                'attribute'=>'ФИО представителя организции',
                'value' => function($data){
                    return $data->employee->fio;
                },
                'filter' => false,
            ],
            [
                'label'=>'Должность',
                'value' => function($data){
                    return $data->employee->position;
                },
                'filter' => false,
            ],
            [
                'label'=>'Страна',
                'value' => function($data){
                    return $data->employee->organization->country->name;
                },
                'filter' => false,
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
            ],
        ],
    ]); ?>
</div>