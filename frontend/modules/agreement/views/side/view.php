<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\SideAgr */

$this->title = $agreement->shortName . ': Сторона соглашения';
$this->params['breadcrumbs'][] = ['label' => $agreement->shortName, 'url'=> Url::to(['/agreement/default/view', 'id' => $agreement->id])];
$this->params['breadcrumbs'][] = ['label' => 'Стороны соглашения', 'url' => Url::to(['/agreement/side/index', 'agreementId' => $agreement->id])];
$this->params['breadcrumbs'][] = 'Сторона соглашения';
?>
<div class="side-agr-view">


    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute'=>'agreement_id',
                'value' => function($model){
                    return $model->agreement->shortName;
                },
            ],
            [
                'attribute'=>'org_id',
                'value' => function($model){
                    return $model->org->name    ;
                },
            ],
            [
                'attribute'=>'employee_id',
                'value' => function($model){
                    return $model->employee->fio;
                },
            ],

            'desc:ntext',
            'subdivision:ntext',
        ],
    ]) ?>

</div>
