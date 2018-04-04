<?php

namespace frontend\modules\mission\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii2tech\spreadsheet\Spreadsheet;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;


/**
 * DefaultController implements the CRUD actions for Mission model.
 */
class ExcellController  extends \frontend\components\BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }



    public function actionIndex()
    {
        $exporter = (new Spreadsheet([
            'title' => 'Sheet 1',
            'dataProvider' => new ArrayDataProvider([
                'allModels' => [
                    [
                        'column1' => '1.1',
                        'column2' => '1.2',
                        'column3' => '1.3',
                        'column4' => '1.4',
                        'column5' => '1.5',
                        'column6' => '1.6',
                        'column7' => '1.7',
                    ],
                    [
                        'column1' => '2.1',
                        'column2' => '2.2',
                        'column3' => '2.3',
                        'column4' => '2.4',
                        'column5' => '2.5',
                        'column6' => '2.6',
                        'column7' => '2.7',
                    ],
                ],
            ]),
            'headerColumnUnions' => [
                [
                    'header' => 'Skip 1 column and group 2 next',
                    'offset' => 1,
                    'length' => 2,
                ],
                [
                    'header' => 'Skip 2 columns and group 2 next',
                    'offset' => 2,
                    'length' => 2,
                ],
            ],
        ]))->render();



        $exporter->configure([
            'title' => 'Sheet2',
            'dataProvider' => new ArrayDataProvider([
                'allModels' => [
                    [
                        'column1' => 'Wee',
                        'column2' => 'sdfsdf',
                        'column3' => 'sdfsdf',
                        'column4' => 'sdf',
                        'column5' => 'sdf',
                        'column6' => 'sdf',
                        'column7' => 'sdf',
                    ],
                    [
                        'column1' => 'sdf',
                        'column2' => 'sdf',
                        'column3' => 'sdf',
                        'column4' => 'sdf',
                        'column5' => 'sdf',
                        'column6' => 'sdf',
                        'column7' => 'asdasdasd',
                    ],
                ],
            ]),
            'headerColumnUnions' => [
                [
                    'header' => 'Skip 1 column and group 2 next',
                    'offset' => 1,
                    'length' => 2,
                ],
                [
                    'header' => 'Skip 2 columns and group 2 next',
                    'offset' => 2,
                    'length' => 2,
                ],
            ],
        ])->render();

        //return $exporter->send('name.xlsx');

        //Использовать таймштамп для имени
        $path = \Yii::getAlias('@common/excel/file.xlsx');
        //$exporter->save($path);
        return $exporter->send('items.xlsx');

        if(file_exists($path)){
            return \Yii::$app->response->sendFile($path);
        }else{
            throw new NotFoundHttpException('Такого файла не существует ');
        }

    }

}
