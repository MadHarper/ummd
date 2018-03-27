<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\jui\AutoComplete;


/* @var $this yii\web\View */
/* @var $model common\models\Organization */
/* @var $form yii\widgets\ActiveForm */
?>




<div class="organization-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact')->textarea(['rows' => 6]) ?>

    <?php
    echo $form->field($model, 'country_id')->widget(Select2::classname(),
        [
            'initValueText' => $model->country ? $model->country->name : '',
            'options' => ['placeholder' => 'Выберите страну'],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 4,
                'ajax' => [
                    'url' => '/catalog/organization/searchid',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) {  return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { console.log(city.text); return  city.text; }'),
            ],
        ]);
    ?>

    <?= $form->field($model, 'city')->widget(
        AutoComplete::className(), [
        'clientOptions' => [
            'source' => $cityList,
        ],
        'options'=>[
            'class'=>'form-control'
        ]
    ])->label('Город');
    ?>

    <?= $form->field($model, 'iogv')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php if(!$model->isNewRecord): ?>
    <div class="org-employee-list">
        <h3>Сотрудники:</h3>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            //'layout' => "{items}\n{pager}",
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
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'urlCreator' => function ($action, $employee, $key, $index) {
                        if ($action === 'view') {
                            $url = Url::to(['/catalog/employee/view', 'id' => $employee->id]);
                            return $url;
                        }

                        if ($action === 'update') {
                            $url = Url::to(['/catalog/employee/update', 'id' => $employee->id]);
                            return $url;
                        }

                        if ($action === 'delete') {
                            $url = Url::to(['/catalog/employee/delete', 'id' => $employee->id]);
                            return $url;
                        }
                    }
                ],
                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
<?php endif;?>

