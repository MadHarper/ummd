<?php

namespace frontend\modules\agreement\controllers;

use common\models\UserToris;
use Yii;
use frontend\models\Document;
use frontend\models\search\DocumentSearch;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Agreement;
use common\models\Mission;
use frontend\forms\DocumentUploadForm;
use frontend\core\forms\DocUploadForm;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\db\Expression;
use yii\db\Query;
use frontend\core\services\DocPrepareSaveService;
use common\services\jobs\DocumentSaveJob;
use common\services\jobs\FileRemoveJob;
use common\services\jobs\PictureSaveJob;
use common\models\Beseda;



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
    public function actionIndex($agreementId)
    {
        $agreement = Agreement::findOne(['id' => $agreementId]);

        if(!$agreement){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model = new DocUploadForm();
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->searchByMasterModel(Yii::$app->request->queryParams, $agreement);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'agreement' => $agreement
        ]);
    }



    public function actionAjaxUpload($agreementId)
    {
        if(Yii::$app->request->isAjax && Yii::$app->request->isPost){

            $agreement = Agreement::findOne(['id' => $agreementId]);
            if(!$agreement){
                throw new NotFoundHttpException('The requested agreement model not found.');
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $form = new DocUploadForm();

            if($form->load(Yii::$app->request->post())){

                $form->document = UploadedFile::getInstance($form, 'document');
                if ($form->upload()) {
                    $saveService = new DocPrepareSaveService($agreement,
                        Yii::$app->user->id,
                        Yii::$app->params['sea']['upload_path'],
                        $form);

                    if($saveService->doPrepare()){
                        return [
                            'result' => true
                        ];
                    }
                }
            }

            return [
                'result' => false
            ];
        }
    }


    //старый контроллер для мультиаплоад виджета
    /*
    public function actionUpload($agreementId)
    {
        if(Yii::$app->request->isAjax && Yii::$app->request->isPost){
            //$agreement = Agreement::find()->where(['id' => $agreementId])->one();
            $agreement = Agreement::findOne(['id' => $agreementId]);
            if(!$agreement){
                throw new NotFoundHttpException('The requested agreement model not found.');
            }

            $model = new DocumentUploadForm();

            $model->documentFile = UploadedFile::getInstance($model, 'documentFile');
            if ($res = $model->upload()) {

                //Создаем новую модель документа, с пока не распарсенным текстом и пока безз ссылки на seafile
                $document               = new Document();
                $document->model        = Agreement::class;
                $document->model_id     = $agreement->id;
                $document->iogv_id      = $agreement->iogv_id;
                $document->user_id      = Yii::$app->user->id;
                $document->origin_name   = $res['base_name'];
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
                        'iogv_id'           => $agreement->iogv_id,
                        //'user_id'       => Yii::$app->user->id,
                    ]));
                }else{
                    Yii::$app->queue->push(new PictureSaveJob([
                        'tempPath'          => Yii::getAlias($uploadPath) . $res['newName'] . '.' . $res['ext'],
                        'document_id'       => $document->id,
                        'newName'           => $res['newName'] . '.' . $res['ext'],
                        'iogv_id'           => $agreement->iogv_id,
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
*/

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

        $agreement = Agreement::findOne(['id' => $document->model_id]);
        if(!$agreement){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $document->visible = false;
        $document->save();

        return $this->redirect(['index', 'agreementId' => $agreement->id]);
    }





    public function actionDeleteList($id)
    {
        $document = $this->findModel($id);

        $masterModel = false;

        switch ($document->model) {
            case Agreement::class :
                $masterModel = Agreement::find()->where(['id' => $document->model_id])->one();
                break;
            case Mission::class :
                $masterModel = Mission::find()->where(['id' => $document->model_id])->one();
                break;
            case Beseda::class :
                $masterModel = Beseda::find()->where(['id' => $document->model_id])->one();
                break;
        }

        if(!$masterModel){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $document->visible = false;
        $document->save();

        return $this->redirect(['list']);
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



    public function prepareQuery(string $query):string
    {
        $query = array_filter(explode(' ', mb_strtolower($query)), 'trim');
        if (count($query) < 2) {
            $query = implode('', $query) . ':*';
        } else {
            $query = implode(' & ', $query) . ':*';
        }
        return $query;
    }



    /**
     * @param string $query
     * @param int $cat
     *
     * @return array
     */
    public function findSuggest(string $query, int $cat = null): array
    {

        /*
        $query = $this->prepareQuery($query);
        $tQuery = (new Query())->from('{{%document}}')
            ->select([
                '{{%tovar}}.id',
                '{{%tovar}}.origin_name',
                new Expression('ts_rank({{%tovar}}.fts,to_tsquery(:q)) as rank'),
            ])
            ->leftJoin('{{%category}}','{{%tovar}}.category_id={{%category}}.id')
            ->where(new Expression("{{%tovar}}.fts  @@ to_tsquery(:q)", [':q' => $query]))
            ->limit(10)
            ->orderBy(['rank' => SORT_DESC]);
        if($cat > 0){
            $tQuery->andWhere(['{{%tovar}}.category_id'=>$cat]);
        }
        return $tQuery->all();
        */

        $query = $this->prepareQuery($query);
        $tQuery = (new Query())->from('{{%document}}')
            ->select([
                '{{%document}}.id',
                '{{%document}}.origin_name',
                new Expression('ts_rank({{%document}}.fts,to_tsquery(:q)) as rank'),
            ])
            //->leftJoin('{{%category}}','{{%tovar}}.category_id={{%category}}.id')
            ->where(new Expression("{{%document}}.fts  @@ to_tsquery(:q)", [':q' => $query]))
            ->andWhere(['{{%document}}.visible' => true])
            ->limit(10)
            ->orderBy(['rank' => SORT_DESC]);

        return $tQuery->all();
    }


    public function actionList(){
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->searchFullText(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /*
    public function actionDocDownload($documentId)
    {
        $document = Document::find()->where(['id' => $documentId, 'visible' => true])->one();
        try{
            if(!$document){
                throw new NotFoundHttpException('Document does not found.');
            }

            $seaFileService = Yii::$app->seaFileService;
            $seaFileService->download($document->iogv_id,
                                      $document->sea_name,
                                      $document->origin_name,
                                      $document->type);

            Yii::$app->response->sendFile(Yii::getAlias('@frontend/web/docs/' . $document->origin_name . "." . $document->type));
            //unlink(Yii::getAlias('@frontend/web/docs/' . 'yep1.docx'));
        }catch (\Exception $e){
            Yii::error("Ошибка скачивания документа " . $e->getMessage());
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    */

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
