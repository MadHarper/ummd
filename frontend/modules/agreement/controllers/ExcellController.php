<?php

namespace frontend\modules\agreement\controllers;

use common\models\Agreement;
use common\models\Employee;
use common\models\Organization;
use Yii;
use common\models\SideAgr;
use common\models\search\SideAgrSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\services\EmployeeOptionsGenerator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * SideController implements the CRUD actions for SideAgr model.
 */
class ExcellController extends \frontend\components\BaseController
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


    public function actionDownload($agreementId){
        if(!$agreement = Agreement::find()->where(['id' => $agreementId])->one()){
            throw new NotFoundHttpException('The requested page does not exist.');
        }


        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('PhpOffice')
            ->setLastModifiedBy('PhpOffice')
            ->setTitle('Командировка - ' . $agreement->name)
            ->setSubject('Командировка - ' . $agreement->name)
            ->setDescription('')
            ->setKeywords('')
            ->setCategory('');


        // Вкладка командировка
        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setTitle("Соглашение");

        $activeSheet->getDefaultColumnDimension()->setWidth(16);
        $activeSheet->setCellValue('A1', "Наименование")->getStyle('A1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('A2', $agreement->name);
        $activeSheet->setCellValue('B1', "Дата начала")->getStyle('B1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('B2', $agreement->date_start);
        $activeSheet->setCellValue('C1', "Дата окончания")->getStyle('C1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('C2', $agreement->date_end);

        $activeSheet->setCellValue('D1', "Состояние")->getStyle('D1')->getFont()->setBold(true);;

        if($agreement->state){
            $activeSheet->setCellValue('D2', $agreement->stateToString);
        }





        $path = \Yii::getAlias('@common/excel/soglashenie.xlsx');


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($path);

        if(file_exists($path)){
            return \Yii::$app->response->sendFile($path);
        }else{
            throw new NotFoundHttpException('Такого файла не существует ');
        }

    }

}
