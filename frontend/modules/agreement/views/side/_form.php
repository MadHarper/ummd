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

    <?php
    echo $form->field($model, 'org_id')->widget(Select2::classname(),
        [
            'initValueText' => $model->org ? $model->org->name : '',
            'options' => ['placeholder' => 'Выберите организацию'],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => '/agreement/side/searchid',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) {  return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { console.log(city.text); return  city.text; }'),
            ],
            'pluginEvents' => [
                "change" => "function() { console.log(this.value); loadEmployee(this.value);}",
            ]
        ]);
    ?>

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

    <?= $form->field($model, 'employee_id')->dropDownList($arrEmployees); ?>



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
        $.get('/agreement/side/list', {
            id: orgId
        }, function(res){
            carSelect.html('');
            carSelect.append(res);      
        });
}
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>