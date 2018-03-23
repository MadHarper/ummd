<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\DocumentType;

/* @var $this yii\web\View */
/* @var $model common\models\search\DocumentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-search">

    <?php $form = ActiveForm::begin([
        'action' => ['list'],
        'method' => 'get',
    ]); ?>

    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'model') ?>

    <?php // echo $form->field($model, 'model_id') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'origin_name') ?>

    <?php echo $form->field($model, 'parsed_content')->label('Содержание') ?>

    <?php echo $form->field($model, 'name') ?>

    <?php echo $form->field($model, 'doc_type_id')
                    ->dropDownList(DocumentType::find()
                                                ->select('name')
                                                ->where(['visible' => true])
                                                ->indexBy('id')
                                                ->column(), ['prompt' => '...']) ?>

    <?php // echo $form->field($model, 'sea_name') ?>

    <?php // echo $form->field($model, 'link') ?>

    <?php // echo $form->field($model, 'visible')->checkbox() ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Очистить', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
