<?php

use yii\grid\GridView;


?>

<div id="mission_emp_grid">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'employee_id',
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
                'attribute'=>'ИОГВ',
                'value' => function($data){
                    return $data->employee->organization->name;
                },
                'filter' => false,
            ],
            [
                'attribute'=>'role',
                'value' => function($data){
                    return $data->memberMissionRole;
                },
                'filter' => false,
            ],
            [
                'attribute'=>'boss',
                'value' => function($data){
                    return $data->boss ? "Да" : "Нет";
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