<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\time\TimePicker;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Beseda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="beseda-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'theme')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'target')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'questions')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'date_start')->widget(DatePicker::class, [
        'type' => DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose' => true,
            'format'    => 'yyyy-mm-dd'
        ]
    ]) ?>

    <?= $form->field($model, 'event_time')->widget(TimePicker::classname(), ['pluginOptions' => [
        'showSeconds' => false,
        'showMeridian' => false,
        'minuteStep' => 1,
    ]]);?>

    <div style="position:relative; top:14px;">
        <label class="control-label" for="beseda_org">Наименование организации</label>
        <div>
            <input type="checkbox" id="with_history"/><span style="position:relative; top:-3px; left:6px">Включая исторические данные</span>
        </div>
    </div>

    <?php
    echo $form->field($model, 'iniciator_id')->widget(Select2::classname(),
        [
            'initValueText' => $model->iniciator_id ? $model->iniciator->name : '',
            'options' => ['placeholder' => 'Выберите организацию', 'id' => 'beseda_org'],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => new JsExpression('give_org'),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) {  return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { console.log(city.text); return  city.text; }'),
            ],
            'pluginEvents' => [
                //"change" => "function() { if(this.value){loadEmployee(this.value); loadAnother(this.value)}}",
            ]
        ])->label("");
    ?>


    <? echo $form->field($model, 'address')->widget(Typeahead::classname(), [
        'options' => ['placeholder' => 'Поиск адреса ...'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('pAddress')",
                'display' => 'pAddress',
                'remote' => [
                    'url' => Url::to(['/beseda/default/address']) . '?search=%QUERY',
                    'wildcard' => '%QUERY'
                ]
            ]
        ]
    ]) ?>


    <?= $form->field($model, 'report_date')->widget(DatePicker::class, [
        'type' => DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose' => true,
            'format'    => 'yyyy-mm-dd'
        ]
    ]) ?>

    <?php if(!$model->isNewRecord): ?>
        <?= $form->field($model, 'control_date')->textInput(['readonly' => true]) ?>
    <?php endif ;?>

    <?php if(!$model->isNewRecord): ?>
        <?= $form->field($model, 'status')->dropDownList($availableStatuses); ?>
    <?php endif ;?>

    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>


    <?php
    echo $form->field($model, 'agreementsArray')->widget(Select2::classname(),
        [
            'initValueText' => $besedaAgreementArr,
            'options' => ['placeholder' => 'Выберите соглашения',
                'multiple' => true, 'id' => 'agreementsArray4',
                //'value' => $missionAgreementArr,
            ],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 4,
                'ajax' => [
                    'url' => '/beseda/default/search-agreement',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) {  console.log(city); return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { console.log(city); return city.text;  }'),
            ],
        ])->label("Соглашения");
    ?>



    <?php if(!$model->isNewRecord): ?>
        <?= $form->field($model, 'report_overdue')->checkbox() ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
$script = <<< JS
    
    function give_org(){
        if($("#with_history").is(":checked")){
            return "/agreement/side/searchid-history";
        }else{
            return "/agreement/side/searchid";
        }
}
JS;
$this->registerJs($script, yii\web\View::POS_HEAD);
?>
