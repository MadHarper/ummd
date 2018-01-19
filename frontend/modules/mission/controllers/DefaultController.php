<?php

namespace frontend\modules\mission\controllers;

use Yii;
use common\models\Mission;
use common\models\search\MissionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Organization;
use common\models\Employee;

/**
 * DefaultController implements the CRUD actions for Mission model.
 */
class DefaultController  extends \frontend\components\BaseController
{
    /**
     * @inheritdoc
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

    /**
     * Lists all Mission models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mission model.
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
     * Creates a new Mission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mission();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $iogvList = $this->getIogvList();

        return $this->render('create', [
            'model' => $model,
            'iogvList' => $iogvList,
        ]);
    }

    /**
     * Updates an existing Mission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $iogvList = $this->getIogvList();

        return $this->render('update', [
            'model' => $model,
            'iogvList' => $iogvList,
        ]);
    }

    /**
     * Deletes an existing Mission model.
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
     * Finds the Mission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mission::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionSearchid($q)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];

        $out['results'] = array_values((new \yii\db\Query())
            ->select(['id', 'name as text'])
            ->from('country')
            ->where(['ilike','name',$q])
            ->limit(10)
            ->all());

        return $out;
    }


    public function actionList($id){
        $employees = Employee::find()
                            ->where(['organization_id' => $id])
                            ->orderBy('fio')
                            ->all();

        $list = "";
        foreach ($employees as $emp){
            $list .= '<option value="' . $emp->id . '">' . $emp->fio . " - " . $emp->position .'</option>';
        }

        return $list;
    }

    private function getIogvList()
    {
        $objectList =  Organization::find()
            ->select(['name', 'id'])
            ->where(['iogv' => true])
            ->indexBy('id')
            ->column();

        return $objectList;
    }


}
