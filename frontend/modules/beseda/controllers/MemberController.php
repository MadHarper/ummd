<?php

namespace frontend\modules\beseda\controllers;

use common\models\Employee;
use common\models\Organization;
use frontend\modules\beseda\forms\BesedaMemberAjaxForm;
use Yii;
use common\models\MissionEmployee;
use common\models\search\BesedaEmployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Beseda;
use frontend\core\services\CheckMissionControlDate;
use common\models\BesedaEmployee;


/**
 * MemberController implements the CRUD actions for MissionEmployee model.
 */
class MemberController extends \frontend\components\BaseController
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
        ];
    }





    public function actionIndex($besedaId)
    {
        if(!$besedaId){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $beseda = Beseda::find()->where(['id' => $besedaId])->one();
        if(!$beseda){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $iogvList = Organization::find()->select(['name', 'id'])
            ->where(['iogv' => true, 'history' => false])
            ->indexBy('id')
            ->column();

        $searchModel = new BesedaEmployeeSearch();
        $dataProvider = $searchModel->searchForBeseda($besedaId);

        $ajaxForm = new BesedaMemberAjaxForm();

        return $this->render('index', [
            'iogvList' => $iogvList,
            'beseda' => $beseda,
            'dataProvider' => $dataProvider,
            'ajaxForm' => $ajaxForm]);
    }


    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');
        if(!$id){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $besedaEmployee = BesedaEmployee::findOne($id);
        if(!$besedaEmployee){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $beseda = Beseda::findOne($besedaEmployee->beseda_id);
        if(!$beseda){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $besedaEmployee->delete();

        return $this->redirect(['index', 'besedaId' => $beseda->id]);
    }


    public function actionAddEmployee()
    {
        if(Yii::$app->request->isAjax){

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $form = new BesedaMemberAjaxForm();

            if($form->load(Yii::$app->request->post())){

                $beseda = Beseda::find()->where(['id' => $form->beseda_id])->one();
                if(!$beseda){
                    return ['result' => 'error', 'errors' => 'Беседа не найдена или вы не имеете прав'];
                }

                $errors = $form->save();

                if(!$errors){

                    return ['result' => 'success'];
                }

                return ['result' => 'error', 'errors' => $errors];
            }
            return ['result' => 'error', 'errors' => 'Ошибка сохранения'];
        }
    }



    public function actionList($id)
    {
        if(Yii::$app->request->isAjax){
            $employees = Employee::find()
                ->where(['organization_id' => $id, 'history' => false])
                ->orderBy('fio')
                ->all();

            $list = "";
            foreach ($employees as $emp){
                $list .= '<option value="' . $emp->id . '">' . $emp->fio . '</option>';
            }

            return $list;
        }
    }


    /**
     * Finds the MissionEmployee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MissionEmployee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MissionEmployee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
