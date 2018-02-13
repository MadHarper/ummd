<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\models\Employee;

/* @var $this yii\web\View */
/* @var $model common\models\SideAgr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="side-agr-form">





    <?php $form = ActiveForm::begin(); ?>

    <div style="position:relative; top:14px;">
        <label class="control-label" for="kukaracha">Наименование организации</label>
        <div>
            <input type="checkbox" id="with"/><span style="position:relative; top:-3px; left:6px">Включая исторические данные</span>
        </div>
    </div>

    <?php
    echo $form->field($model, 'org_id')->widget(Select2::classname(),
        [
            'initValueText' => $model->org ? $model->org->name : '',
            'options' => ['placeholder' => 'Выберите организацию', 'id' => 'kukaracha'],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 3,
                'ajax' => [
                    //'url' => '/agreement/side/searchid',
                    'url' => new JsExpression('foo'),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) {  return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { console.log(city.text); return  city.text; }'),
            ],
            'pluginEvents' => [
                "change" => "function() { if(this.value){loadEmployee(this.value);}}",
            ]
        ])->label("");
    ?>

    <div style="position:relative; top:14px;">
        <label class="control-label" for="kukaracha">ФИО представителя организации</label>
        <div>
            <input type="checkbox" id="with_historic"/><span style="position:relative; top:-3px; left:6px">Включая исторические данные</span>
        </div>
    </div>
    <?php
    $arrEmployees = [];
    if($model->org_id){
        $all = Employee::find()
                ->where(['organization_id' => $model->org_id, 'history' => false])
                ->orderBy(['fio' => SORT_ASC, 'main_id' => SORT_ASC])
                ->all();

        foreach ($all as $item){
            $arrEmployees[$item->id] = $item->fio . " - " . $item->position;
        }
    }
    ?>

    <?= $form->field($model, 'employee_id')->dropDownList($arrEmployees)->label(""); ?>



    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'subdivision')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<< JS
    var org_id;

    function loadEmployee(orgId){
        var carSelect = $('select[name="SideAgr[employee_id]"]');
        var history = $("#with_historic").is(":checked") ? 1 : 0;
        console.log($("#with_historic").attr("checked"));
        $.get('/agreement/side/list', {
            id: orgId,
            history: history
        }, function(res){
            carSelect.html('');
            carSelect.append(res);      
        });
}

    $('#with_historic').change(function(){
         $('#kukaracha').trigger('change');
});
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>


<?php
$script = <<< JS
    
    function foo(){
        if($("#with").is(":checked")){
            return "/agreement/side/searchid-history";
        }else{
            return "/agreement/side/searchid";
        }
}
JS;
$this->registerJs($script, yii\web\View::POS_HEAD);
?>




