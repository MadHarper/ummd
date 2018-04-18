<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use common\models\Agreement;
use yii\web\JsExpression;
use kartik\select2\Select2;


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

    <?= $form->field($model, 'meropriatie')->checkbox() ?>


    <?php
    echo $form->field($model, 'missionsArray')->widget(Select2::classname(),
        [
            'initValueText' => $missionAgreementArr,
            'options' => ['placeholder' => 'Выберите командировки',
                'multiple' => true, 'id' => 'agreementsArray5',
                //'value' => $missionAgreementArr,
            ],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 4,
                'ajax' => [
                    'url' => '/agreement/default/search-mission',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) {  console.log(city); return city.text; }'),
                'templateSelection' => new JsExpression('format'),
            ],
        ])->label("Командировки");
    ?>


    <?php
    echo $form->field($model, 'besedaArray')->widget(Select2::classname(),
        [
            'initValueText' => $besedaAgreementArr,
            'options' => ['placeholder' => 'Выберите беседы',
                'multiple' => true, 'id' => 'agreementsArray6',
                //'value' => $missionAgreementArr,
            ],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 4,
                'ajax' => [
                    'url' => '/agreement/default/search-beseda',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) {  console.log(city); return city.text; }'),
                'templateSelection' => new JsExpression('format2'),
            ],
        ])->label("Беседы");
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
$script = <<< JS
    function format(city) {       
        return '<a href="/mission/default/view?id=' + city.id + '" target="_blank">' + city.text + '</a>';
}

    function format2(city) {       
        return '<a href="/beseda/default/view?id=' + city.id + '" target="_blank">' + city.text + '</a>';
}
JS;
$this->registerJs($script, yii\web\View::POS_HEAD);
?>
