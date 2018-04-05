<?php

namespace frontend\modules\mission\controllers;

use common\models\Document;
use common\models\Mission;
use common\models\MissionEmployee;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

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



    public function actionIndex($missionId)
    {
        if(!$mission = Mission::find()->where(['id' => $missionId])->one()){
            throw new NotFoundHttpException('The requested page does not exist.');
        }



        $spreadsheet = new Spreadsheet();  /*----Spreadsheet object-----*/
        $Excel_writer = new Xls($spreadsheet);


        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet();

        $activeSheet->setTitle("Shitttt");
        $activeSheet->setCellValue('A1' , 'New file content')->getStyle('A1')->getFont()->setBold(true);



        $spreadsheet->setActiveSheetIndex(1);
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setCellValue('B1' , 'Another')->getStyle('B1')->getFont()->setBold(true);



        //header('Content-Type: application/vnd.ms-excel');
        //header('Content-Disposition: attachment;filename="'. 'экспорт' .'.xls"'); /*-- $filename is  xsl filename ---*/
        //header('Cache-Control: max-age=0');

//        $Excel_writer->save('php://output');
//        exit;

        $path = \Yii::getAlias('@common/excel/file.xls');
        $Excel_writer->save($path);
        //return $exporter->send('items.xlsx');

        if(file_exists($path)){
            return \Yii::$app->response->sendFile($path);
        }else{
            throw new NotFoundHttpException('Такого файла не существует ');
        }
    }



    public function actionSecond($missionId){
        if(!$mission = Mission::find()->where(['id' => $missionId])->one()){
            throw new NotFoundHttpException('The requested page does not exist.');
        }


        $spreadsheet = new Spreadsheet();

//        $spreadsheet->getProperties()->setCreator('PhpOffice')
//            ->setLastModifiedBy('PhpOffice')
//            ->setTitle('Office 2007 XLSX Test Document')
//            ->setSubject('Office 2007 XLSX Test Document')
//            ->setDescription('PhpOffice')
//            ->setKeywords('PhpOffice')
//            ->setCategory('PhpOffice');


        // Вкладка командировка
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setTitle("Командировка");

        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet();

        $activeSheet->getDefaultColumnDimension()->setWidth(16);
        $activeSheet->setCellValue('A1', "Наименование")->getStyle('A1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('A2', $mission->name);
        $activeSheet->setCellValue('B1', "Дата начала")->getStyle('B1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('B2', $mission->date_start);
        $activeSheet->setCellValue('C1', "Дата окончания")->getStyle('C1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('C2', $mission->date_end);
        $activeSheet->setCellValue('D1', "Страна")->getStyle('D1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('D2', $mission->country->name);


        if(isset($mission->region_id) && $mission->region){
            $region = $mission->region->name;
        }else{
            $region = "";
        }

        $activeSheet->setCellValue('E1', "Регион")->getStyle('E1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('E2', $region);

        $city = isset($mission->city_id) ? $mission->town->name : " - ";
        $activeSheet->setCellValue('F1', "Город")->getStyle('F1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('F2', $city);

        $activeSheet->setCellValue('G1', "Приказ")->getStyle('G1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('G2', $mission->order);
        $activeSheet->setCellValue('H1', "Организация")->getStyle('H1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('H2', $mission->organization->name);
        $activeSheet->setCellValue('I1', "Отв. за отчет")->getStyle('I1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('I2', $mission->dutyMan->fio);
        $activeSheet->setCellValue('J1', "Служ. пометки")->getStyle('J1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('J2', $mission->notes);
        $activeSheet->setCellValue('K1', "Дата предоставления отчета")->getStyle('K1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('K2', $mission->report_date);
        $activeSheet->setCellValue('L1', "Контрольный срок")->getStyle('L1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('L2', $mission->contol_date);




        // Вкладка Участники
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->getDefaultColumnDimension()->setWidth(30);
        $activeSheet->setTitle("Участники");

        $employess = $mission->employesEntity;

        $activeSheet->setCellValue('A1', "Ф.И.О")->getStyle('A1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('B1', "Должность")->getStyle('B1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('C1', "Организация")->getStyle('C1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('D1', "Роль")->getStyle('D1')->getFont()->setBold(true);;


        $cnt = 2;
        foreach($employess as $emp){

            $activeSheet->setCellValue("A$cnt", $emp->fio);
            $activeSheet->setCellValue("B$cnt", $emp->position);
            $activeSheet->setCellValue("C$cnt", $emp->organization->name);

            $me = MissionEmployee::find()->where(['mission_id' => $mission->id, 'employee_id' => $emp->id])->one();
            $activeSheet->setCellValue("D$cnt", $me->memberMissionRole);

            $cnt++;
        }

        // Вкладка Документы
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(2);
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->getDefaultColumnDimension()->setWidth(30);
        $activeSheet->setTitle("Документы");

        $activeSheet->setCellValue('A1', "Наименование документа")->getStyle('A1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('B1', "Дата документа")->getStyle('B1')->getFont()->setBold(true);;
        $activeSheet->setCellValue('C1', "Тип документа")->getStyle('C1')->getFont()->setBold(true);;

        $docs = Document::find()->where(['visible' => true, 'model' => Mission::className(), 'model_id' => $mission->id])->all();
        $cnt = 2;
        foreach($docs as $doc){
            $activeSheet->setCellValue("A$cnt", $doc->name);
            $activeSheet->setCellValue("B$cnt", $doc->doc_date);

            if($doc->docType){
                $activeSheet->setCellValue("C$cnt", $doc->docType->name);
            }else{
                $activeSheet->setCellValue("C$cnt", " - ");
            }
            $cnt++;
        }


        $path = \Yii::getAlias('@common/excel/file.xlsx');


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($path);

        if(file_exists($path)){
            return \Yii::$app->response->sendFile($path);
        }else{
            throw new NotFoundHttpException('Такого файла не существует ');
        }

    }

}
