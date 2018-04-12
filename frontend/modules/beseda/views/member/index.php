<?php

use yii\helpers\Html;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use common\models\Employee;
use common\models\MissionEmployee;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;



/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MissionEmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $beseda->theme . ': Участники командировки';
$this->params['breadcrumbs'][] = ['label' => $beseda->theme, 'url'=> Url::to(['/beseda/default/view', 'id' => $beseda->id])];
$this->params['breadcrumbs'][] = 'Участники командировки';
?>




<?php $form = ActiveForm::begin([
    'enableAjaxValidation'      => false,
    'enableClientValidation'    => false,
    'validateOnChange'          => false,
    'validateOnSubmit'          => false,
    'validateOnBlur'            => false,
    'id'                        => 'addEmp'
]);?>


<?= $form->field($ajaxForm, 'beseda_id')->hiddenInput(['value' => $beseda->id])->label(""); ?>


<?=
$form->field($ajaxForm, 'iogv')->widget(\yii2mod\chosen\ChosenSelect::className(),[
    'items' => $iogvList,
    'options' => [
        'prompt' => '',
    ]
]);
?>


<div style="position:relative; top:14px;">
    <label class="control-label" for="emp_history">Сотрудник</label>
    <div>
        <input type="checkbox" id="emp_history"/><span style="position:relative; top:-3px; left:6px">Включая исторические данные</span>
    </div>
</div>
<?=
$form->field($ajaxForm, 'employee')->widget(\yii2mod\chosen\ChosenSelect::className(),[
    'items' => [],
])->label("");
?>



<?= Html::submitButton('Добавить', ['class' => 'btn btn-success']);?>
<?php ActiveForm::end();?>


<?php Pjax::begin([
    'id' => 'miss-emp-grid',
    'timeout' => 5000,
    'enablePushState' => false,
])
?>

<?= $this->render('_employee_list', ['dataProvider' => $dataProvider]); ?>

<?php Pjax::end() ?>



<?php
$script = <<< JS
    
    $('#besedamemberajaxform-iogv').on('change', function(evt, params) {
        var orgId = $('#besedamemberajaxform-iogv').val()
        loadEmployee(orgId);
    });

    $('#emp_history').change(function(){
         loadEmployee($('#besedamemberajaxform-iogv').val());
    });


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
            $('#besedamemberajaxform-employee').empty();
            $('#besedamemberajaxform-employee').append(res);
            $('#besedamemberajaxform-employee').trigger("chosen:updated");      
        });
    }
    
    $("#addEmp").on( "submit", function( event ) {
        event.preventDefault();
        
        $.ajax({
            url: "/beseda/member/add-employee",
            type: "POST",
            //dataType: "json",
            data: $(this).serialize(),
            success: function(response) {
                console.log(response);
                if(response.result === "error"){
                    toastr.error(response.errors, "Ошибка: ");
                }else{
                    $.pjax.reload({container:"#miss-emp-grid", timeout: 3000});
                    toastr.success("Добавлен участник командировки");
                }
            },
            error: function(response) {
                console.log("error");
            }
        }); 
        
    });
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>


