<?php

namespace frontend\modules\beseda\controllers;

use Yii;
use common\models\Beseda;
use common\models\search\BesedaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use toris\yii2Widgets\typeAheadAddress\AddressAction;
use frontend\core\services\BesedaStatusService;

/**
 * DefaultController implements the CRUD actions for Beseda model.
 */
class DefaultController extends \frontend\components\BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actions()
    {
        return [
            'address' => AddressAction::class,
        ];
    }


    /**
     * Lists all Beseda models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BesedaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Beseda model.
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
     * Creates a new Beseda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Beseda();
        $model->iogv_id = Yii::$app->user->identity->iogv_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $besedaStatusService = new BesedaStatusService();
            $besedaStatusService->checkAndChangeStatus($model);
            $besedaStatusService->checkControlDay($model);

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Beseda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $besedaStatusService = new BesedaStatusService();
            $besedaStatusService->checkAndChangeStatus($model);
            $besedaStatusService->checkControlDay($model);

            return $this->redirect(['view', 'id' => $model->id]);
        }

        $besedaStatusService = new BesedaStatusService();
        $availableStatuses = $besedaStatusService->getStatusListFromCurrent($model->status);


        return $this->render('update', [
            'model' => $model,
            'availableStatuses' => $availableStatuses
        ]);
    }

    /**
     * Deletes an existing Beseda model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Beseda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Beseda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Beseda::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
