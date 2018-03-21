<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use common\models\Agreement;

/* @var $this yii\web\View */
/* @var $model common\models\Agreement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agreement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if(!$model->isNewRecord): ?>
        <?= $form->field($model, 'status')->dropDownList(Agreement::getStatusList()) ?>
    <?php endif;?>

    <?= $form->field($model, 'name')->textarea(['rows' => 6]) ?>


    <?= $form->field($model, 'date_start')->widget(DatePicker::class, [
        'type' => DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose' => true,
            'format'    => 'yyyy-mm-dd'
        ]
    ]) ?>

    <?= $form->field($model, 'date_end')->widget(DatePicker::class, [
        'type' => DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose' => true,
            'format'    => 'yyyy-mm-dd'
        ]
    ]) ?>


    <?= $form->field($model, 'state')->dropDownList(Agreement::getStateList()) ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
