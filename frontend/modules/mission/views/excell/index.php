<?php

\moonland\phpexcel\Excel::export([
    'fileName' => 'export.xlsx',
    'isMultipleSheet' => true,
    'getOnlySheet' => 'sheet1',
    'models' => [
        'sheet1' => $mission,
        //'sheet2' => $model2
    ],
    'columns' => [
        'sheet1' => [
            [
            'attribute' => 'name',
            //'header' => 'Content Post',
            'format' => 'text',
            'value' => function($mission) {
                return $mission->name;
                }
            ],
            [
                'attribute' => 'date_start',
                'header' => 'Content Post',
                'format' => 'text',
                'value' => function($mission) {
                    return $mission->date_start;
                },
            ],
        ],
        //'sheet2' => ['fio', 'organization_id']
    ],
    'headers' => [
        'sheet1' => ['name' => 'Имя', 'target' => 'Цель'],
        //'sheet2' => ['fio' => 'ФИО', 'organization_id' => 'Организация']
    ],
]);