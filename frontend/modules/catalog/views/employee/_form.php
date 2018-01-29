<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\grid\GridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>


    <?php
    echo $form->field($model, 'organization_id')->widget(Select2::classname(),
        [
            'initValueText' => $model->organization ? $model->organization->name : '',
            'options' => ['placeholder' => 'Выберите организацию'],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => '/catalog/employee/searchid',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) {  return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { console.log(city.text); return  city.text; }'),
            ],
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php if(!$model->isNewRecord && !$model->history): ?>
    <div class="employee-index-history">

        <h3>История:</h3>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'fio',
                    'filter' => false,
                ],
                [
                    'attribute' => 'position',
                    'filter' => false,
                ],
                [
                    'attribute' => 'organization_id',
                    'value' => function($model){
                        return \common\models\Organization::find()->where(['id' => $model->organization_id])->one()->name;
                    },
                    'filter' => false,
                ],
                [
                    'attribute'=>'created_at',
                    'value' => function($model){
                        return date('d.m.Y', $model->updated_at);
                    },
                ],
                [
                    'attribute'=>'updated_at',
                    'value' => function($model){
                        return date('d.m.Y', $model->updated_at);
                    },
                ],
                /*
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}',
                    'urlCreator' => functi ($action, $employee, $key, $index) {
                        /*
                        if ($action === 'view') {
                            $url = Url::to(['/catalog/employee/view', 'id' => $employee->id]);
                            return $url;
                        }


                        if ($action === 'update') {
                            $url = Url::to(['/catalog/employee/update-history', 'id' => $employee->id]);
                            return $url;
                        }
                    }
                ],
                */
            ],
        ]); ?>
    </div>
<?php endif; ?>