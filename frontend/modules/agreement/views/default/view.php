<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Agreement */

$this->title = $model->shortName . "...";
$this->params['breadcrumbs'][] = ['label' => 'Соглашения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-view">


    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                'attribute'=>'status',
                'value' => function($model){
                    return $model->getStatusToString();
                },
            ],
            'name:ntext',
            [
                'label' => 'Стороны соглашения',
                'value' => function ($model) {
                    $str = '';
                    if($model->sideAgrs){
                        $n = 1;

                        foreach ($model->sideAgrs as $side){
                            $str .= "<div class='intable_list'>";
                            $str .= '<div><span class="badge">' . $n .'</span></div>';
                            $str .= '<div>' . $side->org->name . '</div>';
                            $str .= "</div>";
                            $n++;
                        }

                    }

                    return $str;
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'date_start',
                'value' => function($model){
                    return date("d.m.Y", strtotime($model->date_start));
                }
            ],
            [
                'attribute' => 'date_end',
                'value' => function($model){
                    return date("d.m.Y", strtotime($model->date_end));
                }
            ],
            'iogv_id',
            'desc:ntext',
            [
                'attribute'=>'created_at',
                'value' => function($model){
                    return date('d.m.Y', $model->created_at);
                },
            ],
            [
                'attribute'=>'updated_at',
                //'label' => 'Создан',
                'value' => function($model){
                    return date('d.m.Y', $model->updated_at);
                },
            ],
        ],
    ]) ?>

</div>
