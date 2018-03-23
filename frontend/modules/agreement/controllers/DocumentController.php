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
            //$form->document= UploadedFile::getInstance($form, 'document');

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

        //ToDo: !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //ToDo: Разные типы документов!!! Принадлежат не обязательно Agreement!!!!!!!!!!!!
        $agreement = Agreement::findOne(['id' => $document->model_id]);
        if(!$agreement){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $document->visible = false;
        $document->save();

        return $this->redirect(['index', 'agreementId' => $agreement->id]);
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
