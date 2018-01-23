<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

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

    <?= $form->field($model, 'iogv')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
