<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use common\models\Employee;



/* @var $this yii\web\View */
/* @var $model common\models\Mission */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mission-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'organization_id')->widget(Select2::classname(), [
//        'name' => 'iogv-select',
        'data' => $iogvList,
        'options' => [
            'placeholder' => 'Выберите ИОГВ ...',
            'id' => 'sss1',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'pluginEvents' => [
            "change" => "function() { console.log(this.value); loadEmployee(this.value);}",
        ]
    ]);
    ?>


    <?php
    $arrEmployees = [];
    if($model->organization_id){
        $all = Employee::find()->where(['organization_id' => $model->organization_id])->all();
        foreach ($all as $item){
            $arrEmployees[$item->id] = $item->fio . " - " . $item->position;
        }
    }
    ?>

    <?= $form->field($model, 'duty_man_id')->dropDownList($arrEmployees); ?>


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

    <?php
    echo $form->field($model, 'country_id')->widget(Select2::classname(),
        [
            'initValueText' => $model->country ? $model->country->name : '',
            'options' => ['placeholder' => 'Выберите страну', 'id' => 'sss2',],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 3,
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


    <?= $form->field($model, 'region_id')->textInput() ?>

    <?= $form->field($model, 'city')->textInput() ?>

    <?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'target')->textarea(['rows' => 6]) ?>


    <?php
    echo $form->field($model, 'agreementsArray')->widget(Select2::classname(),
        [
            'initValueText' => $missionAgreementArr,
            'options' => ['placeholder' => 'Выберите соглашения',
                          'multiple' => true, 'id' => 'agreementsArray3',
                          //'value' => [5 => "name", 8 => "alex"],
            ],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 4,
                'ajax' => [
                    'url' => '/mission/default/search-agreement',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) {  console.log(city); return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { console.log(city); return city.text;  }'),
            ],
        ])->label("Соглашения");
    ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



<?php
$script = <<< JS
    var org_id;

    function loadEmployee(orgId){
        var carSelect = $('select[name="Mission[duty_man_id]"]');
        $.get('/mission/default/list', {
            id: orgId
        }, function(res){
            carSelect.html('');
            carSelect.append(res);      
        });
}
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>