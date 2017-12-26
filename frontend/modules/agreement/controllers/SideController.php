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
        return $this->render('view', [
            'model' => $this->findModel($id),
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

        //ToDo: выполнить проверку, что пользователь имеет доступ к данному соглашению

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'agreementId' => $model->agreement_id]);
        }

        return $this->render('update', [
            'model' => $model,
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

    public function actionList($id){
        $employees = Employee::find()->where(['organization_id' => $id])->all();

        $list = "";
        foreach ($employees as $emp){
            $list .= '<option value="' . $emp->id . '">' . $emp->fio . " - " . $emp->position .'</option>';
        }

        return $list;
    }

}
