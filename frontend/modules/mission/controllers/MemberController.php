<?php

namespace frontend\modules\mission\controllers;

use common\models\Employee;
use common\models\Organization;
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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

        $model = new MissionMemberForm(['mission' => $mission, 'arr' => []]);
        $iogvList = Organization::find()->select(['name', 'id'])
                                        ->where(['iogv' => true, 'history' => false])
                                        ->indexBy('id')
                                        ->column();

        $errors = [];

        if(Yii::$app->request->isPost){
            $errors = $model->upload(Yii::$app->request->post('MissionMemberForm', []));
            if(!$errors){
                return $this->redirect(['index', 'missionId' => $missionId]);
            }
        }

        return $this->render('index', ['model' => $model,
                                            'iogvList' => $iogvList,
                                            'errors' => $errors,
                                            'mission' => $mission
                                            ]
                            );
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
