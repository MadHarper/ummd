<?php

namespace frontend\modules\beseda\controllers;

use common\models\UserToris;
use common\services\jobs\PictureSaveJob;
use Yii;
use frontend\models\Document;
use frontend\models\search\DocumentSearch;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Beseda;
use frontend\forms\DocumentUploadForm;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\db\Expression;
use yii\db\Query;
use common\services\jobs\DocumentSaveJob;
use common\services\jobs\FileRemoveJob;
use frontend\core\forms\DocUploadForm;
use frontend\core\services\DocPrepareSaveService;




/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends \frontend\components\BaseController
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

    /**
     * Lists all Document models.
     * @return mixed
     * @throws NotFoundHttpException if the Agreement model cannot be found
     */
    public function actionIndex($besedaId)
    {
        if(!$besedaId){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $beseda = Beseda::findOne(['id' => $besedaId]);

        if(!$beseda){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model = new DocUploadForm();
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->searchByMasterModel(Yii::$app->request->queryParams, $beseda);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'beseda' => $beseda
        ]);
    }



    public function actionAjaxUpload($besedaId)
    {
        if(Yii::$app->request->isAjax && Yii::$app->request->isPost){

            $beseda = Beseda::findOne(['id' => $besedaId]);
            if(!$beseda){
                throw new NotFoundHttpException('The requested agreement model not found.');
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $form = new DocUploadForm();

            if($form->load(Yii::$app->request->post())){

                $form->document = UploadedFile::getInstance($form, 'document');
                if ($form->upload()) {
                    $saveService = new DocPrepareSaveService($beseda,
                                                            Yii::$app->user->id,
                                                            Yii::$app->params['sea']['upload_path'],
                                                            $form);

                    if($saveService->doPrepare()){
                        return ['result' => true];
                    }
                }
            }

            return ['result' => false];
        }
    }




    /**
     * Displays a single Document model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }



    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $document = $this->findModel($id);
        $beseda = Beseda::findOne(['id' => $document->model_id]);
        if(!$beseda){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $document->visible = false;
        $document->save();

        return $this->redirect(['index', 'missionId' => $beseda->id]);
    }



    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }





    public function actionDocDownload($documentId)
    {
        $document = Document::find()->where(['id' => $documentId, 'visible' => true])->one();

        if(!$document){
            throw new NotFoundHttpException('Document does not found.');
        }

        $downloadDir = Yii::$app->params['sea']['download_path'];
        $filePath = Yii::getAlias($downloadDir . $document->origin_name . "." . $document->type);

        if(!file_exists($filePath)){
            $seaFileService = Yii::$app->seaFileService;
            $seaFileService->download($document->iogv_id,
                $document->sea_name,
                $document->type,
                $filePath);
        }

        Yii::$app->response->sendFile($filePath);
        Yii::$app->queue->delay(1 * 60)->push(new FileRemoveJob(['path' => $filePath]));
    }

}
