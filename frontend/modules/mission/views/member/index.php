<?php

use yii\helpers\Html;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\models\Employee;
use common\models\MissionEmployee;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MissionEmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Участники командировки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mission-employee-index">


    <?php if($errors): ?>
        <div class="alert alert-danger">
            <strong>Ошибка!</strong><br/>
                <?php foreach ($errors as $error): ?>
                    <?php foreach ($error as $key => $val): ?>
                            • <?= $val;?> <br/>
                    <?php endforeach;?>
                <?php endforeach; ?>
        </div>
    <?php endif;?>

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false,
        'validateOnChange'          => false,
        'validateOnSubmit'          => true,
        'validateOnBlur'            => false,
    ]);?>

    <?= $form->field($model, 'arr')->label("")->widget(MultipleInput::className(), [
        'max' => 150,
        'columns' => [
            [
                'name'  => 'iogv_id',
                'title' => 'ИОГВ',
                'type'  => Select2::class,
                'items' =>  $iogvList,
                'options' => [
                    'name' => 'iogv_id',
                    'value' => function($data){
                        return $data['iogv_id'];
                    },
                    'data' => $iogvList,
                    'options' => ['placeholder' => 'Выберите ИОГВ ...'],
                    'pluginEvents' => [
                        'change' => 'function(e){
                            var parent = $(this).parents(".multiple-input-list__item")[0];
                            var target = $(parent).find(".list-cell__employee_id select")[0];
                            
                            $.get("/mission/member/list", {
                                id: this.value
                            }, function(res){
                                $(target).html("");
                                $(target).append(res);      
                            });
                        }'
                    ]
                ]
            ],

            [
                'name'  => 'employee_id',
                'type'  => 'dropDownList',
                'title' => 'Сотрудник',
                'value' => function($data){
                    return $data['employee_id'];
                },
                'items' => function($data){
                    $employee = Employee::find()->where(['id' => $data['employee_id']])->one();
                    if($employee){
                        return Employee::find()
                            ->select(['fio', 'id'])
                            ->where(['organization_id' => $employee->organization_id])
                            ->indexBy('id')
                            ->column();
                    }
                    return [];
                }
            ],

            [
                'name'  => 'role',
                'type'  => 'dropDownList',
                'title' => 'Степень участия',
                'value' => function($data){
                    return $data['role'];
                },
                'items' => function($data){
                    return MissionEmployee::getMissionRolesList();
                }
            ],
        ],

    ]);
    ?>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']);?>
    <?php ActiveForm::end();?>

</div>


