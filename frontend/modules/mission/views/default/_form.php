<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use common\models\Employee;
use common\models\Organization;



/* @var $this yii\web\View */
/* @var $model common\models\Mission */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mission-form" id="missform" data-nonhistory="<?= preg_replace('#(\"+)#' , '\'', $nonHistoryOrgOptions);?>" data-history="<?= preg_replace('#(\"+)#' , '\'', $historyOrgOptions);?>">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textarea(['rows' => 6]) ?>


    <div style="position:relative; top:14px;">
        <label class="control-label" for="iogv_history">ИОГВ</label>
        <div>
            <input type="checkbox" id="iogv_history"/><span style="position:relative; top:-3px; left:6px">Включая исторические данные</span>
        </div>
    </div>
    <?=
         $form->field($model, 'organization_id')->widget(\yii2mod\chosen\ChosenSelect::className(),[
            'items' => $iogvList,
             'options' => [
                 'prompt' => '',
             ]
         ])->label("");
    ?>


    <?php
        $arrEmployees = [];
        if($model->organization_id){
            $org = Organization::findOne($model->organization_id);
            $id = $org->id;

            if($org->history){
                $currentOrg = Organization::find()
                                ->where(['history' => false, 'main_id' => $org->main_id])
                                ->one();

                $id = $currentOrg->id;
            }

            $all = Employee::find()
                    ->where(['organization_id' => $id])
                    ->andWhere(['or', ['id'=> $model->duty_man_id], ['history' => false]])
                    ->all();

            foreach ($all as $item){
                $arrEmployees[$item->id] = $item->fio . " - " . $item->position;
            }
        }
    ?>

    <div style="position:relative; top:14px;">
        <label class="control-label" for="emp_history">Ответственный за предоставление отчета</label>
        <div>
            <input type="checkbox" id="emp_history"/><span style="position:relative; top:-3px; left:6px">Включая исторические данные</span>
        </div>
    </div>
    <?=
        $form->field($model, 'duty_man_id')->widget(\yii2mod\chosen\ChosenSelect::className(),[
            'items' => $arrEmployees,
        ])->label("");
    ?>

    <?php
        /*
        echo $form->field($model, 'duty_man_id')->dropDownList($arrEmployees)->label("");
        */
     ?>


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
    var dataNonHistory = $('#missform').data('nonhistory'); 
    var dataHistory = $('#missform').data('history'); 
    var empHistoric = 0;
    
    /*
    function loadEmployee(orgId){
        var carSelect = $('select[name="Mission[duty_man_id]"]');
        $.get('/mission/default/list', {
            id: orgId
        }, function(res){
            carSelect.html('');
            carSelect.append(res);      
        });
    }
    */
    function loadEmployee(orgId){
        if($("#emp_history").is(":checked")){
            empHistoric = 1;
        }else{
            empHistoric = 0;
        }
        
        $.get('/mission/default/list', {
            id: orgId,
            historic: empHistoric
        }, function(res){
            console.log(res);
            $('#mission-duty_man_id').empty();
            $('#mission-duty_man_id').append(res);
            $('#mission-duty_man_id').trigger("chosen:updated");      
        });
    }
    
    
    $('#mission-organization_id').on('change', function(evt, params) {
        loadEmployee($(this).val());
    });
    
    $('#emp_history').change(function(){
         loadEmployee($('#mission-organization_id').val());
    });
    
    $('#iogv_history').change(function(){
         if($("#iogv_history").is(":checked")){
            $('#mission-organization_id').empty();
            $('#mission-organization_id').append(dataHistory);
            $('#mission-organization_id').trigger("chosen:updated");
        }else{
            $('#mission-organization_id').empty();
            $('#mission-organization_id').append(dataNonHistory);
            $('#mission-organization_id').trigger("chosen:updated");
        }
});
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>


