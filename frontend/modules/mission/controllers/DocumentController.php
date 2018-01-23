<?php

namespace frontend\modules\mission\controllers;

use common\models\UserToris;
use common\services\jobs\PictureSaveJob;
use Yii;
use frontend\models\Document;
use frontend\models\search\DocumentSearch;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Mission;
use frontend\forms\DocumentUploadForm;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\db\Expression;
use yii\db\Query;
use common\services\jobs\DocumentSaveJob;
use common\services\jobs\FileRemoveJob;



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
    public function actionIndex($missionId)
    {
        if(!$missionId){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $mission = Mission::findOne(['id' => $missionId]);

        if(!$mission){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model = new DocumentUploadForm();
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->searchByMasterModel(Yii::$app->request->queryParams, $mission);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'mission' => $mission
        ]);
    }



    public function actionUpload($missionId)
    {
        if(Yii::$app->request->isAjax && Yii::$app->request->isPost){

            $mission = Mission::findOne(['id' => $missionId]);
            if(!$mission){
                throw new NotFoundHttpException('The mission model not found.');
            }

            $model = new DocumentUploadForm();

            $model->documentFile = UploadedFile::getInstance($model, 'documentFile');
            if ($res = $model->upload()) {

                //Создаем новую модель документа, с пока не распарсенным текстом и пока безз ссылки на seafile
                $document               = new Document();
                $document->model        = Mission::class;
                $document->model_id     = $mission->id;

                $document->iogv_id      = $mission->iogv_id;

                $document->user_id      = Yii::$app->user->id;
                $document->origin_name  = $res['base_name'];
                $document->sea_name     = $res['newName'];
                $document->status       = Document::STATUS_NOT_PROCESSED;
                $document->type         = $res['ext'];
                $document->save();

                // Помещаем документ в очередь
                $uploadPath = Yii::$app->params['sea']['upload_path'];

                if($res['ext'] === 'docx'){
                    Yii::$app->queue->push(new DocumentSaveJob([
                        'tempPath'          => Yii::getAlias($uploadPath) . $res['newName'] . '.' . $res['ext'],
                        'document_id'       => $document->id,
                        'newName'           => $res['newName'] . '.' . $res['ext'],
                        'iogv_id'           => $mission->iogv_id,
                        //'user_id'       => Yii::$app->user->id,
                    ]));
                }else{
                    Yii::$app->queue->push(new PictureSaveJob([
                        'tempPath'          => Yii::getAlias($uploadPath) . $res['newName'] . '.' . $res['ext'],
                        'document_id'       => $document->id,
                        'newName'           => $res['newName'] . '.' . $res['ext'],
                        'iogv_id'           => $mission->iogv_id,
                    ]));
                }



                //отдаем json для виджета мультиаплоад
                return Json::encode([
                    'files' => [
                        [
                            'name' => $res['base_name'] . '.' . $res['ext'],
                            'size' => '',
                            'url' => '/',
                            'thumbnailUrl' => false,
                            'deleteUrl' => 'image-delete?name=',
                            'deleteType' => 'POST',
                        ],
                    ],
                ]);
            }

            return Json::encode([
                'files' => [[
                    'error' => "Неверный формат файла",
                ]]
            ]);
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
        $mission = Mission::findOne(['id' => $document->model_id]);
        if(!$mission){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $document->visible = false;
        $document->save();

        return $this->redirect(['index', 'missionId' => $mission->id]);
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
