<?php

namespace frontend\modules\catalog\controllers;

use frontend\modules\catalog\services\OrganizationUpdateService;
use Yii;
use common\models\Organization;
use common\models\search\OrganizationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\search\EmployeeSearch;
use common\models\City;

/**
 * OrganizationController implements the CRUD actions for Organization model.
 */
class OrganizationController extends \frontend\components\BaseController
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
                        'roles' => ['viewDirectory']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['searchid'],
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
     * Lists all Organization models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrganizationSearch();
        $dataProvider = $searchModel->searchNonHistoric(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Organization model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $searchModel = new OrganizationSearch();
        $dataProvider = $searchModel->searchWithHistory(Yii::$app->request->queryParams, $id, $model->main_id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider
        ]);
    }





    /**
     * Creates a new Organization model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Organization();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if($model->city){
               $city_id = $this->checkOrAddCity($model->city);
                   if($city_id){
                        $model->city_id = $city_id;
                   }
            }

            $model->main_id = $model->id;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $cityList = $this->getCitiesList();

        return $this->render('create', [
            'model' => $model,
            'cityList' => $cityList
        ]);
    }

    /**
     * Updates an existing Organization model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->city_id){
            $currentCity = City::find()->where(['id' => $model->city_id])->one();
            if($currentCity){
                $model->city = $currentCity->name;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if($model->city){
                $city_id = $this->checkOrAddCity($model->city);
                   if($city_id){
                       $model->city_id = $city_id;
                   }
            }


            $orgUpdateService = new OrganizationUpdateService($model);
            $redirectId = $orgUpdateService->update();
            return $this->redirect(['view', 'id' => $redirectId]);
        }

        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->searchByOrganization(Yii::$app->request->queryParams, $model->id);

        $cityList = $this->getCitiesList();

        return $this->render('update', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'cityList' => $cityList
        ]);
    }

    /**
     * Deletes an existing Organization model.
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
     * Finds the Organization model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Organization the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Organization::findOne($id)) !== null) {
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


    private function getCitiesList()
    {
        $cityList = City::find()
            ->select(['name as value', 'name as label'])
            ->asArray()
            ->all();

        return $cityList;
    }


    private function checkOrAddCity($cityName)
    {
        $id = false;
        $cases = City::find()->where(['ilike', 'name', $cityName])->all();

        if($cases){
            foreach ($cases as $case){
                if(mb_strtolower($case->name) == mb_strtolower($cityName))
                {
                    $id = $case->id;
                    return $id;
                }
            }
        }

        $newCity = new City();
        $newCity->name = $cityName;
        if($newCity->save()){
            return $newCity->id;
        }

        return $id;
    }

}
