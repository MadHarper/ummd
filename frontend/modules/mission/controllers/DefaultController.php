<?php

namespace frontend\modules\mission\controllers;

use common\models\Region;
use Yii;
use common\models\Mission;
use common\models\search\MissionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Organization;
use common\models\Employee;
use common\models\Country;
use common\models\Agreement;
use frontend\services\EmployeeOptionsGenerator;
use common\models\City;
use frontend\core\services\MissionStatusService;
use frontend\core\services\CheckMissionControlDate;

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
     * Lists all Mission models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $iogvList = $this->getIogvList();
        $countryList = Country::find()
                            ->select(['name', 'id'])
                            ->indexBy('id')
                            ->column();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'iogvList' => $iogvList,
            'countryList' => $countryList,
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

        $model->iogv_id = Yii::$app->user->identity->iogv_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $checkControlDate = new CheckMissionControlDate($model->id);
            $checkControlDate->check();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $regions = Region::find()->select('name')->indexBy('id')->orderBy('name')->column();
        $cityList = $this->getCitiesList();
        $iogvList = $this->getIogvList();


        $missionAgreementArr = [];
        $model->_agreements = null;

        $nonHistoryOrgOptions = $this->getNonHistoryOrgOptions();
        $historyOrgOptions = $this->getHistoryOrgOptions();

        return $this->render('create', [
            'model' => $model,
            'iogvList' => $iogvList,
            'missionAgreementArr' => $missionAgreementArr,
            'nonHistoryOrgOptions' => $nonHistoryOrgOptions,
            'historyOrgOptions' => $historyOrgOptions,
            'regions' => $regions,
            'cityList' => $cityList,
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
        $statusService = new MissionStatusService();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try{
                $statusService->checkNewStatus($model);
                $model->save();
                $checkControlDate = new CheckMissionControlDate($model->id);
                $checkControlDate->check();
                return $this->redirect(['view', 'id' => $model->id]);
            }catch (\DomainException $exception){
                \Yii::$app->session->setFlash('error', $exception->getMessage());
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        $regions = Region::find()->select('name')->indexBy('id')->column();
        $iogvList = $this->getIogvList($model->organization_id);
        $nonHistoryOrgOptions = $this->getNonHistoryOrgOptions();
        $historyOrgOptions = $this->getHistoryOrgOptions();

        $missionAgreementArr = Agreement::find()
            ->where(['id' => $model->agreementsArray])
            ->select("name, id")
            ->indexBy("id")
            ->orderBy("id")
            ->column();


        $cityList = $this->getCitiesList();

        $availableStatuses = $statusService->getStatusListFromCurrent($model->status);

        return $this->render('update', [
            'model' => $model,
            'iogvList' => $iogvList,
            'missionAgreementArr' => $missionAgreementArr,
            'nonHistoryOrgOptions' => $nonHistoryOrgOptions,
            'historyOrgOptions' => $historyOrgOptions,
            'regions' => $regions,
            'cityList' => $cityList,
            'availableStatuses' => $availableStatuses
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
        $model = $this->findModel($id);
        $model->visible = false;
        $model->save();

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
        $model = Mission::find()->where(['id' => $id, 'visible' => true])->one();

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
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


    public function actionList($id, $historic = 0){

        $generator = new EmployeeOptionsGenerator();
        $list = $generator->generateOptions($id, $historic);
        return $list;
    }

    private function getIogvList($orgId = null)
    {
        $query =  Organization::find()
            ->select(['name', 'id'])
            ->where(['iogv' => true]);

        if($orgId){
            $query->andWhere(['or', ['id'=> $orgId], ['history' => false]]);
        }else{
            $query->andWhere([ 'history' => false]);
        }
        $query->indexBy('id');

        return $query->column();
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

    public function actionSearchAgreement($q)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];

        $out['results'] = array_values((new \yii\db\Query())
            ->select(['id', "name as text"])
            ->from('agreement')
            ->where(['ilike','name',$q])
            ->limit(10)
            ->all());

        return $out;
    }

    public function getHistoryOrgOptions()
    {
        $objectList =  Organization::find()->where(['iogv' => true])->all();
        $res = '';
        foreach ($objectList as $o){
            $style = $o->history ? 'class="historic_drop"' : '';
            $res .= "<option value='" . $o->id . "' " . $style .">" . $o->name . "</option>";
        }

        return $res;
    }

    public function getNonHistoryOrgOptions()
    {
        $objectList =  Organization::find()->where(['iogv' => true, 'history' => false])->all();
        $res = '';
        foreach ($objectList as $o){
            $res .= "<option value='" . $o->id . "'>" . $o->name . "</option>";
        }

        return $res;
    }


    private function getCitiesList()
    {
        $cityList = City::find()
            ->select(['name as value', 'name as label'])
            ->asArray()
            ->all();

        return $cityList;
    }

}
