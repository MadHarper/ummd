<?php

namespace frontend\modules\agreement\controllers;

use common\models\Agreement;
use common\models\Employee;
use Yii;
use common\models\SideAgr;
use common\models\search\SideAgrSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\services\EmployeeOptionsGenerator;

/**
 * SideController implements the CRUD actions for SideAgr model.
 */
class SideController extends \frontend\components\BaseController
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
     * Lists all SideAgr models.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionIndex($agreementId)
    {
        if(!$agreementId){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $agreement = Agreement::find()->where(['id' => $agreementId])->one();
        if(!$agreement){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        //ToDo: выполнить проверку, что пользователь имеет доступ к данному соглашению



        $searchModel = new SideAgrSearch();
        $dataProvider = $searchModel->searchByAgreement(Yii::$app->request->queryParams, $agreementId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'agreement' => $agreement
        ]);
    }

    /**
     * Displays a single SideAgr model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $agreement = Agreement::find()->where(['id' => $model->agreement_id])->one();
        if(!$agreement){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'model' => $model,
            'agreement' => $agreement,
        ]);
    }

    /**
     * Creates a new SideAgr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCreate($agreementId)
    {
        if(!$agreementId){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $agreement = Agreement::find()->where(['id' => $agreementId])->one();
        if(!$agreement){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model = new SideAgr();

        if ($model->load(Yii::$app->request->post())) {
            $model->agreement_id = $agreementId;
            if($model->save()){
                return $this->redirect(['index', 'agreementId' => $agreementId]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'agreement' => $agreement,
        ]);
    }

    /**
     * Updates an existing SideAgr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $agreement = Agreement::find()->where(['id' => $model->agreement_id])->one();
        if(!$agreement){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'agreementId' => $model->agreement_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'agreement' => $agreement,
        ]);
    }

    /**
     * Deletes an existing SideAgr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $side = $this->findModel($id);
        $agreement = Agreement::findOne(['id' => $side->agreement_id]);
        if(!$agreement){
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }

        $side->delete();

        return $this->redirect(['index', 'agreementId' => $agreement->id]);
    }

    /**
     * Finds the SideAgr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SideAgr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SideAgr::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionList($id, $historic = 0){

        $generator = new EmployeeOptionsGenerator();
        $list = $generator->generateOptions($id, $historic);
        return $list;
    }



    public function actionSearchid($q)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];

        $out['results'] = array_values((new \yii\db\Query())
            ->select(['id', 'name as text', 'history'])
            ->from('organization')
            ->where(['ilike','name',$q])
            ->andWhere(['history' => false])
            ->limit(10)
            ->all());

        return $out;
    }

    public function actionSearchidHistory($q)
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

}
