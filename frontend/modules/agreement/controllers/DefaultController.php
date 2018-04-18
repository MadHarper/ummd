<?php

namespace frontend\modules\agreement\controllers;

use common\models\Beseda;
use Yii;
use common\models\Agreement;
use common\models\search\AgreementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Mission;

/**
 * DefaultController implements the CRUD actions for Agreement model.
 */
class DefaultController extends \frontend\components\BaseController
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
     * Lists all Agreement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgreementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Agreement model.
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
     * Creates a new Agreement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Agreement();
        $model->iogv_id = Yii::$app->user->identity->iogv_id;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect(['update', 'id' => $model->id]);
        }

        $missionAgreementArr = [];
        $besedaAgreementArr = [];

        return $this->render('create', [
            'model' => $model,
            'missionAgreementArr' => $missionAgreementArr,
            'besedaAgreementArr' => $besedaAgreementArr
        ]);
    }

    /**
     * Updates an existing Agreement model.
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

        $missionAgreementArr = Mission::find()
            ->where(['id' => $model->missionsArray])
            ->select("name, id")
            ->indexBy("id")
            ->orderBy("id")
            ->column();

        $besedaAgreementArr = Beseda::find()
            ->where(['id' => $model->besedaArray])
            ->select("theme, id")
            ->indexBy("id")
            ->orderBy("id")
            ->column();

        return $this->render('update', [
            'model' => $model,
            'missionAgreementArr' => $missionAgreementArr,
            'besedaAgreementArr' => $besedaAgreementArr
        ]);
    }

    /**
     * Deletes an existing Agreement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        //ToDo в будущем будет журнал согласования с КВС об удалении.
        //  Надо будет позаботиться о всех удаляемых сущностях из промежуточных таблиц

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }



    public function actionSearchOrg($q)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];

        $out['results'] = array_values((new \yii\db\Query())
            ->select(['id', 'name as text'])
            ->from('organization')
            ->where(['ilike','name',$q])
            ->limit(10)
            ->all());

        return $out;
    }

    public function actionSearchEmployee($q)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];

        $out['results'] = array_values((new \yii\db\Query())
            ->select(['id', "CONCAT (fio, ' - ', position) as text"])
            ->from('employee')
            ->where(['ilike','fio',$q])
            ->limit(10)
            ->all());

        return $out;
    }




    /**
     * Finds the Agreement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Agreement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agreement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionSearchMission($q)
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $out = ['results' => ['id' => '', 'text' => '']];

            $out['results'] = array_values((new \yii\db\Query())
                ->select(['id', "name as text"])
                ->from('mission')
                ->where(['ilike','name',$q])
                ->limit(10)
                ->all());

            return $out;
        }
    }


    public function actionSearchBeseda($q)
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $out = ['results' => ['id' => '', 'text' => '']];

            $out['results'] = array_values((new \yii\db\Query())
                ->select(['id', "theme as text"])
                ->from('beseda')
                ->where(['ilike','theme',$q])
                ->limit(10)
                ->all());

            return $out;
        }
    }
}
