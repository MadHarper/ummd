<?php

namespace frontend\modules\mission\controllers;

use common\models\Employee;
use common\models\Organization;
use frontend\modules\mission\forms\MissionMemberAjaxForm;
use frontend\modules\mission\forms\MissionMemberForm;
use Yii;
use common\models\MissionEmployee;
use common\models\search\MissionEmployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Mission;

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





    public function actionIndex($missionId)
    {
        if(!$missionId){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $mission = Mission::find()->where(['id' => $missionId])->one();
        if(!$mission){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $iogvList = Organization::find()->select(['name', 'id'])
            ->where(['iogv' => true, 'history' => false])
            ->indexBy('id')
            ->column();

        $searchModel = new MissionEmployeeSearch();
        $dataProvider = $searchModel->searchForMission($missionId);

        $ajaxForm = new MissionMemberAjaxForm();

        return $this->render('index', ['iogvList' => $iogvList,
                                            'mission' => $mission,
                                            'dataProvider' => $dataProvider,
                                            'ajaxForm' => $ajaxForm]);
    }


    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');
        if(!$id){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $missionEmployee = MissionEmployee::findOne($id);
        if(!$missionEmployee){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $mission = Mission::findOne($missionEmployee->mission_id);
        if(!$mission){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $missionEmployee->delete();

        return $this->redirect(['index', 'missionId' => $mission->id]);
    }


    public function actionAddEmployee()
    {
        if(Yii::$app->request->isAjax){

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $form = new MissionMemberAjaxForm();

            if($form->load(Yii::$app->request->post())){

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
